<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderClientMail;
use App\Mail\NewOrderAdminMail;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $rawPlanId = $request->query('plan_id');
        // Ensure plan_id is a scalar (string/int); handle array inputs safely
        $planId = is_array($rawPlanId) ? ($rawPlanId[0] ?? null) : $rawPlanId;
        $plan = null;
        if (!empty($planId)) {
            $plan = PricingPlan::where('is_active', true)->find($planId);
        }

        $availablePaymentMethods = \App\Services\PaymentService::getAvailablePaymentMethods();
        $defaultPaymentMethod = \App\Services\PaymentService::getDefaultPaymentMethod();

        $safePlanId = '';
        if (!empty($plan?->id)) {
            $safePlanId = (string) $plan->id;
        } elseif (!empty($planId)) {
            $safePlanId = (string) $planId;
        }

        // For iframe contexts, ensure CSRF token is cached
        // This is critical for mobile Safari where cookies are blocked
        // We'll cache the token that Laravel generates (or create one if session doesn't exist)
        $token = null;
        
        // Try to get token from session first
        if ($request->hasSession() && $request->session()->token()) {
            $token = $request->session()->token();
            \Illuminate\Support\Facades\Log::info('CheckoutController: CSRF token retrieved from session', [
                'has_session' => true,
                'token_preview' => substr($token, 0, 10) . '...',
                'referer' => $request->header('Referer'),
            ]);
        } else {
            \Illuminate\Support\Facades\Log::warning('CheckoutController: No session or session token', [
                'has_session' => $request->hasSession(),
                'referer' => $request->header('Referer'),
            ]);
        }
        
        // If we have a token, cache it for iframe validation
        if ($token) {
            $cacheKey = 'iframe_csrf_tokens';
            $validTokens = \Illuminate\Support\Facades\Cache::get($cacheKey, []);
            $validTokens[$token] = time();
            
            // Keep only tokens from last 2 hours
            $now = time();
            $cleanedTokens = [];
            foreach ($validTokens as $cachedToken => $timestamp) {
                if (($now - $timestamp) < 7200) { // 2 hours
                    $cleanedTokens[$cachedToken] = $timestamp;
                }
            }
            
            \Illuminate\Support\Facades\Cache::put($cacheKey, $cleanedTokens, 7200);
            
            \Illuminate\Support\Facades\Log::info('CheckoutController: CSRF token cached', [
                'token_preview' => substr($token, 0, 10) . '...',
                'total_cached_tokens' => count($cleanedTokens),
            ]);
        }

        return view('checkout', [
            'plan' => $plan,
            'planId' => $safePlanId,
            'availablePaymentMethods' => $availablePaymentMethods,
            'defaultPaymentMethod' => $defaultPaymentMethod,
        ]);
    }

    public function submit(Request $request)
    {
        $sourceRule = 'nullable|string|max:255';
        if (class_exists('App\\Models\\Source') && \Illuminate\Support\Facades\Schema::hasTable('sources')) {
            $sourceRule = 'nullable|string|max:255|exists:sources,name';
        }

        // Preserve plan_id for redirect on validation failure
        $planId = $request->input('pricing_plan_id') ?? $request->query('plan_id');

        try {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'subscription_type' => 'required|in:new,renewal',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
            'source' => $sourceRule,
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirect back with errors and preserve plan_id
            return redirect()->route('checkout.show', ['plan_id' => $planId])
                ->withErrors($e->errors())
                ->withInput();
        }

        $plan = PricingPlan::findOrFail($validated['pricing_plan_id']);

        // Determine source from request query or payload (default to 'main')
        $sourceFromRequest = $request->query('source') ?? ($validated['source'] ?? 'main');

        // Find or create client user by email (case-insensitive)
        $email = strtolower($validated['email']);
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        
        if (!$user) {
            $user = User::create([
                'email' => $email,
                'name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'password' => bcrypt(str()->random(16)),
                'role' => 'client',
                'source' => $sourceFromRequest,
            ]);
        }

        // Update user information if needed (name, phone, source)
        $updateData = [];
        if ($user->name !== $validated['full_name']) {
            $updateData['name'] = $validated['full_name'];
        }
        if ($user->phone !== $validated['phone']) {
            $updateData['phone'] = $validated['phone'];
        }
        if (empty($user->source)) {
            $updateData['source'] = $sourceFromRequest;
        }
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Create a payment intent to proceed to selected gateway
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
                'source' => $sourceFromRequest,
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

        // Redirect to payment gateway route (public versions)
        return match ($validated['payment_method']) {
            'stripe' => redirect()->route('public.payment.stripe', $paymentIntent),
            'paypal' => redirect()->route('public.payment.paypal', $paymentIntent),
            'crypto' => redirect()->route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => redirect()->route('public.payment.coinbase-commerce', $paymentIntent),
            default => redirect()->route('checkout.show')->with('error', 'Unsupported payment method'),
        };
    }
}


