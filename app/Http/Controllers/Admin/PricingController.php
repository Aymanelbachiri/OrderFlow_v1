<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\SystemSetting;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pricingPlans = PricingPlan::orderBy('plan_type')
            ->orderBy('server_type')
            ->orderBy('device_count')
            ->orderBy('duration_months')
            ->get()
            ->groupBy(['plan_type', 'server_type', 'device_count']);

        return view('admin.pricing.index', compact('pricingPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pricing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'server_type' => 'required|in:basic,premium',
            'plan_type' => 'required|in:regular,reseller',
            'device_count' => 'required|integer|min:1|max:10',
            'duration_months' => 'required|integer|min:1|max:24',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'payment_link' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        // Generate name
        $planTypePrefix = $validated['plan_type'] === 'reseller' ? 'Reseller ' : '';
        $validated['name'] = $planTypePrefix . ucfirst($validated['server_type']) . ' - ' .
                           $validated['device_count'] . ' Device(s) - ' .
                           $validated['duration_months'] . ' Month(s)';

        PricingPlan::create($validated);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PricingPlan $pricing)
    {
        $pricingPlan = $pricing;
        $pricingPlan->loadCount('orders');
        return view('admin.pricing.show', compact('pricingPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PricingPlan $pricing)
    {
        $pricingPlan = $pricing;
        return view('admin.pricing.edit', compact('pricingPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PricingPlan $pricing)
    {
        $validated = $request->validate([
            'server_type' => 'required|in:basic,premium',
            'plan_type' => 'required|in:regular,reseller',
            'device_count' => 'required|integer|min:1|max:10',
            'duration_months' => 'required|integer|min:1|max:24',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'payment_link' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        // Update name
        $planTypePrefix = $validated['plan_type'] === 'reseller' ? 'Reseller ' : '';
        $validated['name'] = $planTypePrefix . ucfirst($validated['server_type']) . ' - ' .
                           $validated['device_count'] . ' Device(s) - ' .
                           $validated['duration_months'] . ' Month(s)';

        $pricing->update($validated);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PricingPlan $pricing)
    {
        $pricing->delete();

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing plan deleted successfully.');
    }

    /**
     * Show payment configuration
     */
    public function paymentConfig()
    {
        $settings = [
            'primary_payment_method' => SystemSetting::get('primary_payment_method', 'paypal'),
            'stripe_enabled' => SystemSetting::get('stripe_enabled', false),
            'stripe_public_key' => SystemSetting::get('stripe_public_key', ''),
            'stripe_secret_key' => SystemSetting::get('stripe_secret_key', ''),
            'paypal_enabled' => SystemSetting::get('paypal_enabled', false),
            'paypal_mode' => SystemSetting::get('paypal_mode', 'sandbox'),
            'paypal_sandbox_client_id' => SystemSetting::get('paypal_sandbox_client_id', ''),
            'paypal_sandbox_client_secret' => SystemSetting::get('paypal_sandbox_client_secret', ''),
            'paypal_live_client_id' => SystemSetting::get('paypal_live_client_id', ''),
            'paypal_live_client_secret' => SystemSetting::get('paypal_live_client_secret', ''),
            'crypto_enabled' => SystemSetting::get('crypto_enabled', false),
            'crypto_wallet_address' => SystemSetting::get('crypto_wallet_address', ''),
            'coinbase_commerce_enabled' => SystemSetting::get('coinbase_commerce_enabled', false),
            'coinbase_commerce_api_key' => SystemSetting::get('coinbase_commerce_api_key', ''),
            'coinbase_commerce_webhook_secret' => SystemSetting::get('coinbase_commerce_webhook_secret', ''),
        ];

        return view('admin.pricing.payment-config', compact('settings'));
    }

    /**
     * Update payment configuration
     */
    public function updatePaymentConfig(Request $request)
    {
        // Handle checkbox values properly (unchecked checkboxes don't send values)
        $request->merge([
            'stripe_enabled' => $request->has('stripe_enabled'),
            'paypal_enabled' => $request->has('paypal_enabled'),
            'crypto_enabled' => $request->has('crypto_enabled'),
            'coinbase_commerce_enabled' => $request->has('coinbase_commerce_enabled'),
        ]);

        // Basic validation rules
        $rules = [
            'primary_payment_method' => 'required|in:stripe,paypal,crypto,coinbase_commerce,multiple',
            'stripe_enabled' => 'boolean',
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'paypal_enabled' => 'boolean',
            'paypal_mode' => 'required|in:sandbox,live',
            'paypal_sandbox_client_id' => 'nullable|string',
            'paypal_sandbox_client_secret' => 'nullable|string',
            'paypal_live_client_id' => 'nullable|string',
            'paypal_live_client_secret' => 'nullable|string',
            'crypto_enabled' => 'boolean',
            'crypto_wallet_address' => 'nullable|string',
            'coinbase_commerce_enabled' => 'boolean',
            'coinbase_commerce_api_key' => 'nullable|string',
            'coinbase_commerce_webhook_secret' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Additional validation: if a payment method is enabled, require its credentials
        if ($validated['stripe_enabled']) {
            $request->validate([
                'stripe_public_key' => 'required|string|min:10|starts_with:pk_',
                'stripe_secret_key' => 'required|string|min:10|starts_with:sk_',
            ], [
                'stripe_public_key.starts_with' => 'Stripe publishable key must start with "pk_"',
                'stripe_secret_key.starts_with' => 'Stripe secret key must start with "sk_"',
            ]);
        }

        if ($validated['paypal_enabled']) {
            // Require both sandbox and live credentials
            $request->validate([
                'paypal_sandbox_client_id' => 'required|string|min:10',
                'paypal_sandbox_client_secret' => 'required|string|min:10',
                'paypal_live_client_id' => 'required|string|min:10',
                'paypal_live_client_secret' => 'required|string|min:10',
            ], [
                'paypal_sandbox_client_id.required' => 'PayPal Sandbox Client ID is required',
                'paypal_sandbox_client_secret.required' => 'PayPal Sandbox Client Secret is required',
                'paypal_live_client_id.required' => 'PayPal Live Client ID is required',
                'paypal_live_client_secret.required' => 'PayPal Live Client Secret is required',
            ]);
        }

        if ($validated['crypto_enabled']) {
            $request->validate([
                'crypto_wallet_address' => 'required|string|min:10',
            ]);
        }

        if ($validated['coinbase_commerce_enabled']) {
            $request->validate([
                'coinbase_commerce_api_key' => 'required|string|min:10',
                'coinbase_commerce_webhook_secret' => 'required|string|min:10',
            ], [
                'coinbase_commerce_api_key.required' => 'Coinbase Commerce API Key is required',
                'coinbase_commerce_webhook_secret.required' => 'Coinbase Commerce Webhook Secret is required',
            ]);
        }

        // Validate that at least one payment method is enabled
        if (!$validated['stripe_enabled'] && !$validated['paypal_enabled'] && !$validated['crypto_enabled'] && !$validated['coinbase_commerce_enabled']) {
            return redirect()->back()
                ->withErrors(['primary_payment_method' => 'At least one payment method must be enabled.'])
                ->withInput();
        }

        // Save settings
        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, is_bool($value) ? 'boolean' : 'string');
        }

        // Log the configuration update
        \Illuminate\Support\Facades\Log::info('Payment configuration updated', [
            'primary_method' => $validated['primary_payment_method'],
            'stripe_enabled' => $validated['stripe_enabled'],
            'paypal_enabled' => $validated['paypal_enabled'],
            'crypto_enabled' => $validated['crypto_enabled'],
            'paypal_mode' => $validated['paypal_mode'],
        ]);

        return redirect()->route('admin.payment.config')
            ->with('success', 'Payment configuration updated successfully! All changes have been saved.');
    }
}
