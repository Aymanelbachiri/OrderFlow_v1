<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomProduct;
use App\Models\Client;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\Schema;

class CustomProductCheckoutController extends Controller
{
    public function show(Request $request, CustomProduct $product)
    {
        // Check if product is available
        if (!$product->isAvailable()) {
            return redirect()->route('blog')->with('error', 'This product is currently unavailable.');
        }

        $availablePaymentMethods = \App\Services\PaymentService::getAvailablePaymentMethods();
        $defaultPaymentMethod = \App\Services\PaymentService::getDefaultPaymentMethod();

        $source = $request->query('source', 'custom_product');

        return view('custom-product-checkout', [
            'product' => $product,
            'availablePaymentMethods' => $availablePaymentMethods,
            'defaultPaymentMethod' => $defaultPaymentMethod,
            'source' => $source,
        ]);
    }

    public function submit(Request $request, CustomProduct $product)
    {
        // Check if product is still available
        if (!$product->isAvailable()) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        $sourceRule = 'nullable|string|max:255';
        if (class_exists('App\\Models\\Source') && \Illuminate\Support\Facades\Schema::hasTable('sources')) {
            $sourceRule = 'nullable|string|max:255|exists:sources,name';
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
            'source' => $sourceRule,
        ]);

        $sourceFromRequest = $request->query('source') ?? ($validated['source'] ?? 'custom_product');

        // Find or create client by email
        $client = Client::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'password' => bcrypt(str()->random(16)),
            ]
        );

        // Update client information if needed (name, phone)
        $updateData = [];
        if ($client->name !== $validated['full_name']) {
            $updateData['name'] = $validated['full_name'];
        }
        if ($client->phone !== $validated['phone']) {
            $updateData['phone'] = $validated['phone'];
        }
        
        if (!empty($updateData)) {
            $client->update($updateData);
        }

        // Determine admin_id from source or product
        $adminId = $product->admin_id;
        if (!$adminId && class_exists('App\\Models\\Source') && \Illuminate\Support\Facades\Schema::hasTable('sources')) {
            $sourceModel = \App\Models\Source::where('name', $sourceFromRequest)->first();
            if ($sourceModel && $sourceModel->admin_id) {
                $adminId = $sourceModel->admin_id;
            }
        }

        // Create a payment intent for the custom product
        $paymentIntent = PaymentIntent::create([
            'client_id' => $client->id,
            'admin_id' => $adminId,
            'pricing_plan_id' => null, // No pricing plan for custom products
            'payment_intent_id' => 'pi_temp_' . uniqid(),
            'payment_method' => $validated['payment_method'],
            'amount' => $product->price,
            'currency' => 'USD',
            'status' => 'pending',
            'order_type' => 'subscription', // Use 'subscription' as it's allowed in the ENUM constraint
            'order_data' => [
                'client_id' => $client->id,
                'custom_product_id' => $product->id,
                'source' => $sourceFromRequest,
                'order_type' => 'custom_product',
                'customer' => [
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ],
            ],
            'expires_at' => now()->addHour(),
        ]);

        // Redirect to payment gateway route
        return match ($validated['payment_method']) {
            'stripe' => redirect()->route('public.payment.stripe', $paymentIntent),
            'paypal' => redirect()->route('public.payment.paypal', $paymentIntent),
            'crypto' => redirect()->route('public.payment.crypto', $paymentIntent),
            'coinbase_commerce' => redirect()->route('public.payment.coinbase-commerce', $paymentIntent),
            default => redirect()->route('blog')->with('error', 'Unsupported payment method'),
        };
    }
}

