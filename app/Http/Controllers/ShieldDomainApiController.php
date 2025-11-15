<?php

namespace App\Http\Controllers;

use App\Models\ShieldDomain;
use App\Models\Source;
use App\Models\PricingPlan;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShieldDomainApiController extends Controller
{
    /**
     * Get configuration for a shield domain
     * Returns Source config, pricing plans, and other data needed by the static template
     */
    public function getConfig(Request $request)
    {
        $domain = $request->get('domain') ?? $request->header('Host');
        
        // Remove port if present
        $domain = preg_replace('/:\d+$/', '', $domain);
        
        $shieldDomain = ShieldDomain::where('domain', $domain)->first();
        
        if (!$shieldDomain) {
            return response()->json([
                'error' => 'Shield domain not found',
            ], 404);
        }

        // Get the first active source using this shield domain (or create a default config)
        $source = Source::where('shield_domain_id', $shieldDomain->id)
            ->where('is_active', true)
            ->where('use_shield_domain', true)
            ->first();

        if (!$source) {
            // Return basic config without source-specific data
            return response()->json([
                'template' => $shieldDomain->template_name,
                'domain' => $shieldDomain->domain,
                'pricing_plans' => PricingPlan::where('is_active', true)->get()->map(function ($plan) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'price' => $plan->price,
                        'currency' => 'USD',
                        'duration_months' => $plan->duration_months,
                        'device_count' => $plan->device_count,
                        'server_type' => $plan->server_type,
                    ];
                }),
                'api_base_url' => config('app.url') . '/api',
            ]);
        }

        // Return full config with source data
        return response()->json([
            'template' => $shieldDomain->template_name,
            'domain' => $shieldDomain->domain,
            'source' => [
                'name' => $source->name,
                'company_name' => $source->company_name,
                'contact_email' => $source->contact_email,
                'phone_number' => $source->phone_number,
                'website' => $source->website,
            ],
            'pricing_plans' => PricingPlan::where('is_active', true)->get()->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'currency' => 'USD',
                    'duration_months' => $plan->duration_months,
                    'device_count' => $plan->device_count,
                    'server_type' => $plan->server_type,
                ];
            }),
            'payment_methods' => \App\Services\PaymentService::getAvailablePaymentMethods(),
            'api_base_url' => config('app.url') . '/api',
            'checkout_url' => config('app.url') . '/api/shield-domain/checkout/submit',
            'renewal_url' => config('app.url') . '/api/shield-domain/renewal',
        ]);
    }

    /**
     * Initialize checkout - get available plans and payment methods
     */
    public function initCheckout(Request $request)
    {
        $domain = $request->get('domain') ?? $request->header('Host');
        $domain = preg_replace('/:\d+$/', '', $domain);
        
        $shieldDomain = ShieldDomain::where('domain', $domain)->first();
        
        if (!$shieldDomain) {
            return response()->json(['error' => 'Shield domain not found'], 404);
        }

        $source = Source::where('shield_domain_id', $shieldDomain->id)
            ->where('is_active', true)
            ->where('use_shield_domain', true)
            ->first();

        return response()->json([
            'pricing_plans' => PricingPlan::where('is_active', true)->get(),
            'payment_methods' => \App\Services\PaymentService::getAvailablePaymentMethods(),
            'default_payment_method' => \App\Services\PaymentService::getDefaultPaymentMethod(),
            'source' => $source ? $source->name : 'main',
        ]);
    }

    /**
     * Submit checkout - process payment (reuses CheckoutController logic)
     */
    public function submitCheckout(Request $request)
    {
        $domain = $request->get('domain') ?? $request->header('Host');
        $domain = preg_replace('/:\d+$/', '', $domain);
        
        $shieldDomain = ShieldDomain::where('domain', $domain)->first();
        
        if (!$shieldDomain) {
            return response()->json(['error' => 'Shield domain not found'], 404);
        }

        $source = Source::where('shield_domain_id', $shieldDomain->id)
            ->where('is_active', true)
            ->where('use_shield_domain', true)
            ->first();

        $sourceName = $source ? $source->name : 'main';

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'subscription_type' => 'required|in:new,renewal',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
        ]);

        $plan = PricingPlan::findOrFail($validated['pricing_plan_id']);

        // Find or create client user
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'password' => bcrypt(str()->random(16)),
                'role' => 'client',
                'source' => $sourceName,
            ]
        );

        // Update user info if needed
        $updateData = [];
        if ($user->name !== $validated['full_name']) {
            $updateData['name'] = $validated['full_name'];
        }
        if ($user->phone !== $validated['phone']) {
            $updateData['phone'] = $validated['phone'];
        }
        if (empty($user->source)) {
            $updateData['source'] = $sourceName;
        }
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Create payment intent
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
                'source' => $sourceName,
                'order_type' => 'subscription',
                'subscription_type' => $validated['subscription_type'],
                'starts_at' => now(),
                'expires_at' => now()->addMonths($plan->duration_months),
                'customer' => [
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ],
            ],
            'expires_at' => now()->addHour(),
        ]);

        // Return payment gateway URL
        $paymentUrl = match ($validated['payment_method']) {
            'stripe' => route('public.payment.stripe', $paymentIntent),
            'paypal' => route('public.payment.paypal', $paymentIntent),
            'crypto' => route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => route('public.payment.coinbase-commerce', $paymentIntent),
            default => null,
        };

        if (!$paymentUrl) {
            return response()->json(['error' => 'Unsupported payment method'], 400);
        }

        return response()->json([
            'success' => true,
            'payment_url' => $paymentUrl,
            'payment_intent_id' => $paymentIntent->id,
        ]);
    }

    /**
     * Lookup order for renewal
     */
    public function renewalLookup(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_number', $validated['order_number'])
            ->whereHas('user', function ($q) use ($validated) {
                $q->where('email', $validated['email']);
            })
            ->with(['user', 'pricingPlan'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order' => [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'expires_at' => $order->expires_at,
                'plan' => $order->pricingPlan ? [
                    'id' => $order->pricingPlan->id,
                    'name' => $order->pricingPlan->name,
                    'price' => $order->pricingPlan->price,
                ] : null,
            ],
        ]);
    }

    /**
     * Show renewal form data
     */
    public function renewalShow(Request $request, $orderNumber)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return response()->json(['error' => 'Email is required'], 400);
        }

        $order = Order::where('order_number', $orderNumber)
            ->whereHas('user', function ($q) use ($email) {
                $q->where('email', $email);
            })
            ->with(['user', 'pricingPlan'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order' => [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'expires_at' => $order->expires_at,
                'plan' => $order->pricingPlan ? [
                    'id' => $order->pricingPlan->id,
                    'name' => $order->pricingPlan->name,
                    'price' => $order->pricingPlan->price,
                    'duration_months' => $order->pricingPlan->duration_months,
                ] : null,
            ],
            'available_plans' => PricingPlan::where('is_active', true)->get(),
            'payment_methods' => \App\Services\PaymentService::getAvailablePaymentMethods(),
        ]);
    }

    /**
     * Submit renewal - process renewal payment (reuses PublicRenewalController logic)
     */
    public function renewalSubmit(Request $request, $orderNumber)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
        ]);

        $order = Order::where('order_number', $orderNumber)
            ->whereHas('user', function ($q) use ($validated) {
                $q->where('email', $validated['email']);
            })
            ->with(['user', 'pricingPlan'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $plan = PricingPlan::findOrFail($validated['pricing_plan_id']);
        $user = $order->user;
        $sourceName = $order->source ?? 'renewal';

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
                'source' => $sourceName,
                'order_type' => 'subscription',
                'subscription_type' => 'renewal',
                'original_order_id' => $order->id,
                'starts_at' => now(),
                'expires_at' => now()->addMonths($plan->duration_months),
            ],
            'expires_at' => now()->addHour(),
        ]);

        // Return payment gateway URL
        $paymentUrl = match ($validated['payment_method']) {
            'stripe' => route('public.payment.stripe', $paymentIntent),
            'paypal' => route('public.payment.paypal', $paymentIntent),
            'crypto' => route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => route('public.payment.coinbase-commerce', $paymentIntent),
            default => null,
        };

        if (!$paymentUrl) {
            return response()->json(['error' => 'Unsupported payment method'], 400);
        }

        return response()->json([
            'success' => true,
            'payment_url' => $paymentUrl,
            'payment_intent_id' => $paymentIntent->id,
        ]);
    }
}
