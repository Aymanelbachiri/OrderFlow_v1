<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\ResellerCreditPack;
use App\Models\User;
use App\Models\PaymentIntent;

class ResellerCheckoutController extends Controller
{
    public function show(Request $request)
    {
        $rawPlanId = $request->query('plan_id');
        $planId = is_array($rawPlanId) ? ($rawPlanId[0] ?? null) : $rawPlanId;
        $creditPack = null;
        if (!empty($planId)) {
            $creditPack = ResellerCreditPack::active()->find($planId);
        }

        $availablePaymentMethods = \App\Services\PaymentService::getAvailablePaymentMethods();
        $defaultPaymentMethod = \App\Services\PaymentService::getDefaultPaymentMethod();

        return view('reseller-checkout', [
            'creditPack' => $creditPack,
            'planId' => (string) ($creditPack?->id ?? $planId ?? ''),
            'availablePaymentMethods' => $availablePaymentMethods,
            'defaultPaymentMethod' => $defaultPaymentMethod,
        ]);
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'panel_username' => 'required|string|max:255',
            'panel_password' => 'nullable|string|max:255',
            'reseller_credit_pack_id' => 'required|exists:reseller_credit_packs,id',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
            'source' => 'nullable|string|max:255',
        ]);

        // Determine source from request (default to 'reseller')
        $sourceFromRequest = $request->query('source') ?? ($validated['source'] ?? 'reseller');

        // Ensure selected pack is a valid, active reseller credit pack
        $creditPack = ResellerCreditPack::active()->find($validated['reseller_credit_pack_id']);
        if (!$creditPack) {
            return back()
                ->withInput()
                ->withErrors(['reseller_credit_pack_id' => 'Selected reseller pack is not available. Please choose a valid reseller pack.']);
        }

        // Find or create reseller user by email (case-insensitive)
        $email = strtolower($validated['email']);
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        
        if (!$user) {
            $user = User::create([
                'email' => $email,
                'name' => $validated['full_name'],
                'password' => bcrypt(str()->random(16)),
                'role' => 'reseller',
                'phone' => $validated['phone'],
                'reseller_panel_username' => $validated['panel_username'],
                'reseller_panel_password' => null,
                'source' => $sourceFromRequest,
            ]);
        }
        // Ensure role/fields are updated if user exists
        $user->update([
            'role' => 'reseller',
            'name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'reseller_panel_username' => $validated['panel_username'],
            'reseller_panel_password' => null,
            // Preserve original source if already set, else set now
            'source' => $user->source ?: $sourceFromRequest,
        ]);

        // Create payment intent for reseller credit pack/order
        $paymentIntent = PaymentIntent::create([
            'user_id' => $user->id,
            'reseller_credit_pack_id' => $creditPack->id,
            'payment_intent_id' => 'pi_temp_' . uniqid(),
            'payment_method' => $validated['payment_method'],
            'amount' => $creditPack->price,
            'currency' => 'USD',
            'status' => 'pending',
            'order_type' => 'credit_pack',
            'order_data' => [
                'user_id' => $user->id,
                // IMPORTANT: orders table requires pricing_plan_id (used for both plans and credit packs)
                'pricing_plan_id' => $creditPack->id,
                // Also set reseller_credit_pack_id for proper relationship
                'reseller_credit_pack_id' => $creditPack->id,
                'order_type' => 'credit_pack',
                'starts_at' => now(),
                // Credit pack orders do not expire
                'expires_at' => null,
                'source' => $sourceFromRequest,
                'reseller_username' => $validated['panel_username'],
                'reseller_password' => null,
                'customer' => [
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'panel_username' => $validated['panel_username'],
                ],
            ],
            'expires_at' => now()->addHour(),
        ]);

        // Redirect to payment gateway (public routes)
        return match ($validated['payment_method']) {
            'stripe' => redirect()->route('public.payment.stripe', $paymentIntent),
            'paypal' => redirect()->route('public.payment.paypal', $paymentIntent),
            'crypto' => redirect()->route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => redirect()->route('public.payment.coinbase-commerce', $paymentIntent),
            default => redirect()->route('reseller.checkout.show')->with('error', 'Unsupported payment method'),
        };
    }
}


