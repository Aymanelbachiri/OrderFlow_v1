<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\Log;

class PublicRenewalController extends Controller
{
    /**
     * Lookup subscription by email or order number (public, no auth)
     */
    public function lookup(Request $request)
    {
        $email = $request->query('email');
        $orderNumber = $request->query('order_number');
        $source = $request->query('source');
        
        $subscriptions = collect();
        
        if ($email || $orderNumber) {
            $query = Order::where('order_type', 'subscription')
                ->where('status', '!=', 'cancelled')
                ->with(['user', 'pricingPlan']);
            
            if ($email) {
                $query->whereHas('user', function($q) use ($email) {
                    $q->where('email', $email);
                });
            }
            
            if ($orderNumber) {
                $query->where('order_number', 'like', "%{$orderNumber}%");
            }
            
            $subscriptions = $query->latest('expires_at')->get();
        }
        
        return view('public.renewal-lookup', compact('subscriptions', 'email', 'orderNumber', 'source'));
    }

    /**
     * Show renewal page for a specific order (public, no auth)
     * Uses order number and email for verification
     */
    public function show(Request $request, $orderNumber)
    {
        $email = $request->query('email');
        $source = $request->query('source');
        
        if (!$email) {
            return redirect()->route('renewal.lookup')
                ->with('error', 'Please provide your email address to renew your subscription.');
        }
        
        $order = Order::where('order_number', $orderNumber)
            ->where('order_type', 'subscription')
            ->whereHas('user', function($q) use ($email) {
                $q->where('email', $email);
            })
            ->with(['user', 'pricingPlan'])
            ->first();
        
        if (!$order) {
            return redirect()->route('renewal.lookup')
                ->with('error', 'Subscription not found. Please check your order number and email address.');
        }
        
        // Check if order can be renewed
        if ($order->status === 'cancelled') {
            return redirect()->route('renewal.lookup')
                ->with('error', 'This subscription has been cancelled and cannot be renewed.');
        }
        
        // Get the same pricing plan (if still active) or suggest alternatives
        $originalPlan = $order->pricingPlan;
        $samePlan = null;
        
        if ($originalPlan && $originalPlan->is_active) {
            $samePlan = $originalPlan;
        }
        
        // Get available options from active pricing plans
        $availablePlans = PricingPlan::where('is_active', true)
            ->where('plan_type', 'regular')
            ->get();
        
        // Get unique options
        $availableServerTypes = $availablePlans->pluck('server_type')->unique()->values()->toArray();
        $availableDeviceCounts = $availablePlans->pluck('device_count')->unique()->sort()->values()->toArray();
        $availableDurations = $availablePlans->pluck('duration_months')->unique()->sort()->values()->toArray();
        
        // Get all plans as JSON for JavaScript matching
        $plansJson = $availablePlans->map(function($plan) {
            return [
                'id' => $plan->id,
                'server_type' => $plan->server_type,
                'device_count' => $plan->device_count,
                'duration_months' => $plan->duration_months,
                'price' => $plan->price,
                'display_name' => $plan->display_name,
            ];
        })->toJson();
        
        // Get available payment methods
        $availablePaymentMethods = \App\Services\PaymentService::getAvailablePaymentMethods();
        $defaultPaymentMethod = \App\Services\PaymentService::getDefaultPaymentMethod();
        
        // Calculate days until expiry
        $daysUntilExpiry = $order->daysUntilExpiry();
        $isExpired = $order->isExpired();
        
        return view('public.renewal', compact(
            'order',
            'samePlan',
            'availablePaymentMethods',
            'defaultPaymentMethod',
            'daysUntilExpiry',
            'isExpired',
            'availableServerTypes',
            'availableDeviceCounts',
            'availableDurations',
            'plansJson',
            'source'
        ));
    }

    /**
     * Process renewal checkout (public, no auth)
     */
    public function submit(Request $request, $orderNumber)
    {
        $email = $request->input('email');
        $sourceFromQuery = $request->query('source'); // Capture source from URL query parameter
        
        // Validate email is provided
        if (!$email) {
            return back()->withInput()
                ->withErrors(['email' => 'Email address is required.']);
        }
        
        // Find the original order
        $originalOrder = Order::where('order_number', $orderNumber)
            ->where('order_type', 'subscription')
            ->whereHas('user', function($q) use ($email) {
                $q->where('email', $email);
            })
            ->with(['user', 'pricingPlan'])
            ->first();
        
        if (!$originalOrder) {
            return redirect()->route('renewal.lookup')
                ->with('error', 'Subscription not found. Please check your order number and email address.');
        }
        
        // Validate source
        $sourceRule = 'nullable|string|max:255';
        if (class_exists('App\\Models\\Source') && \Illuminate\Support\Facades\Schema::hasTable('sources')) {
            $sourceRule = 'nullable|string|max:255|exists:sources,name';
        }
        
        // Validate the renewal form
        $validated = $request->validate([
            'email' => 'required|email',
            'pricing_plan_id' => 'nullable|exists:pricing_plans,id',
            'server_type' => 'nullable|in:basic,premium',
            'device_count' => 'nullable|integer|min:1',
            'duration_months' => 'nullable|integer|min:1',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
            'subscription_type' => 'required|in:new,renewal',
            'source' => $sourceRule,
        ]);
        
        // Find pricing plan - either by ID or by matching criteria
        if ($validated['pricing_plan_id']) {
            $plan = PricingPlan::findOrFail($validated['pricing_plan_id']);
        } elseif ($validated['server_type'] && $validated['device_count'] && $validated['duration_months']) {
            // Find plan by matching criteria
            $plan = PricingPlan::where('is_active', true)
                ->where('plan_type', 'regular')
                ->where('server_type', $validated['server_type'])
                ->where('device_count', $validated['device_count'])
                ->where('duration_months', $validated['duration_months'])
                ->first();
            
            if (!$plan) {
                return back()->withInput()
                    ->withErrors(['pricing_plan_id' => 'No pricing plan found for the selected combination. Please try different options.']);
            }
        } else {
            return back()->withInput()
                ->withErrors(['pricing_plan_id' => 'Please select a plan or choose plan options.']);
        }
        
        // Get or update user from original order
        $user = $originalOrder->user;
        
        // Update user info if provided
        if ($request->filled('full_name')) {
            $user->update(['name' => $request->input('full_name')]);
        }
        if ($request->filled('phone')) {
            $user->update(['phone' => $request->input('phone')]);
        }
        
        // Determine source: prioritize query param (from URL), then form input, then default 'renewal'
        // Do NOT use original order source - renewal should use the new source from the URL/form
        $sourceFromRequest = $validated['source'] ?? null;
        
        // Use source from query parameter (URL) first, then form input, then default
        $source = $sourceFromQuery ?? $sourceFromRequest ?? 'renewal';
        
        // Always keep existing credentials for renewals
        $renewalCredentials = null;
        if ($originalOrder) {
            $renewalCredentials = [
                'subscription_username' => $originalOrder->subscription_username,
                'subscription_password' => $originalOrder->subscription_password,
                'subscription_url' => $originalOrder->subscription_url,
                'devices' => $originalOrder->devices,
            ];
        }
        
        // Create payment intent for renewal
        $paymentIntent = PaymentIntent::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $plan->id,
            'payment_intent_id' => 'pi_temp_' . uniqid(),
            'payment_method' => $validated['payment_method'],
            'amount' => $plan->price,
            'currency' => 'USD',
            'status' => 'pending',
            'order_type' => 'subscription',
            'order_data' => [
                'user_id' => $user->id,
                'pricing_plan_id' => $plan->id,
                'source' => $source, // This is the source from URL/form, NOT from original order
                'order_type' => 'subscription',
                'subscription_type' => 'renewal', // Mark as renewal
                'renewal_of_order_id' => $originalOrder->id, // Link to original order
                'renewal_of_order_number' => $originalOrder->order_number,
                'starts_at' => $originalOrder->expires_at ?? now(), // Start from expiry of old order
                'expires_at' => ($originalOrder->expires_at ?? now())->addMonths($plan->duration_months),
                'credentials_option' => 'keep', // Always keep existing credentials for renewals
                'renewal_credentials' => $renewalCredentials,
                'customer' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
            ],
            'expires_at' => now()->addHour(),
        ]);
        
        // Redirect to payment gateway
        return match ($validated['payment_method']) {
            'stripe' => redirect()->route('public.payment.stripe', $paymentIntent),
            'paypal' => redirect()->route('public.payment.paypal', $paymentIntent),
            'crypto' => redirect()->route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => redirect()->route('public.payment.coinbase-commerce', $paymentIntent),
            default => redirect()->route('renewal.show', ['orderNumber' => $orderNumber, 'email' => $email])
                ->with('error', 'Unsupported payment method'),
        };
    }

    /**
     * Quick renewal - one-click renewal with same plan (public, no auth)
     */
    public function quickRenew(Request $request, $orderNumber)
    {
        $email = $request->query('email');
        $source = $request->query('source');
        $token = $request->query('token'); // Optional security token
        
        if (!$email) {
            return redirect()->route('renewal.lookup')
                ->with('error', 'Email address is required.');
        }
        
        $order = Order::where('order_number', $orderNumber)
            ->where('order_type', 'subscription')
            ->whereHas('user', function($q) use ($email) {
                $q->where('email', $email);
            })
            ->with(['user', 'pricingPlan'])
            ->first();
        
        if (!$order || !$order->pricingPlan) {
            return redirect()->route('renewal.lookup')
                ->with('error', 'Subscription not found or plan no longer available.');
        }
        
        // Check if same plan is still active
        if (!$order->pricingPlan->is_active) {
            return redirect()->route('renewal.show', [
                'orderNumber' => $orderNumber,
                'email' => $email,
                'source' => $source
            ])->with('error', 'Your original plan is no longer available. Please select a new plan.');
        }
        
        $user = $order->user;
        $plan = $order->pricingPlan;
        
        // Use the same payment method as the original order, if still available
        $originalPaymentMethod = $order->payment_method;
        
        // Check if the original payment method is still available/configured
        if ($originalPaymentMethod && \App\Services\PaymentService::isPaymentMethodAvailable($originalPaymentMethod)) {
            $paymentMethod = $originalPaymentMethod;
        } else {
            // Fall back to default payment method if original is not available
            $paymentMethod = \App\Services\PaymentService::getDefaultPaymentMethod();
        }
        
        // Determine source: prioritize query param, then default 'renewal'
        // Do NOT use original order source - renewal should use the new source from the URL
        $renewalSource = $source ?? 'renewal';
        
        // Create payment intent
        $paymentIntent = PaymentIntent::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $plan->id,
            'payment_intent_id' => 'pi_temp_' . uniqid(),
            'payment_method' => $paymentMethod,
            'amount' => $plan->price,
            'currency' => 'USD',
            'status' => 'pending',
            'order_type' => 'subscription',
            'order_data' => [
                'user_id' => $user->id,
                'pricing_plan_id' => $plan->id,
                'source' => $renewalSource,
                'order_type' => 'subscription',
                'subscription_type' => 'renewal',
                'renewal_of_order_id' => $order->id,
                'renewal_of_order_number' => $order->order_number,
                'starts_at' => $order->expires_at ?? now(),
                'expires_at' => ($order->expires_at ?? now())->addMonths($plan->duration_months),
                'customer' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
            ],
            'expires_at' => now()->addHour(),
        ]);
        
        // Redirect to payment gateway
        return match ($paymentMethod) {
            'stripe' => redirect()->route('public.payment.stripe', $paymentIntent),
            'paypal' => redirect()->route('public.payment.paypal', $paymentIntent),
            'crypto' => redirect()->route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => redirect()->route('public.payment.coinbase-commerce', $paymentIntent),
            default => redirect()->route('renewal.show', [
                'orderNumber' => $orderNumber,
                'email' => $email
            ])->with('error', 'No payment method configured.'),
        };
    }
}

