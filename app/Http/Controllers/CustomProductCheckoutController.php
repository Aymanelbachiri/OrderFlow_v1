<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomProduct;
use App\Models\User;
use App\Models\PaymentIntent;
use App\Services\HotPlayerService;
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

        // Build validation rules dynamically based on custom fields
        $validationRules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'payment_method' => \App\Services\PaymentService::getPaymentMethodValidationRules(),
            'source' => $sourceRule,
        ];

        // Add MAC address validation for HotPlayer activation products
        if ($product->product_type === 'hotplayer_activation') {
            $validationRules['mac_address'] = 'required|string|max:50';
        }

        // Add validation for custom fields
        if ($product->custom_fields && is_array($product->custom_fields)) {
            foreach ($product->custom_fields as $index => $field) {
                $fieldKey = "custom_fields.{$index}";
                $fieldType = $field['type'] ?? 'text';
                $isRequired = $field['required'] ?? false;
                
                $baseRule = $isRequired ? 'required' : 'nullable';
                
                // Add type-specific validation
                switch ($fieldType) {
                    case 'email':
                        $validationRules[$fieldKey] = "{$baseRule}|email|max:255";
                        break;
                    case 'number':
                        $validationRules[$fieldKey] = "{$baseRule}|numeric";
                        break;
                    case 'checkbox':
                        $options = $field['options'] ?? [];
                        if (!empty($options)) {
                            // Multiple checkboxes - validate as array
                            $validationRules[$fieldKey] = $isRequired 
                                ? "required|array|min:1"
                                : "nullable|array";
                            $validationRules["{$fieldKey}.*"] = "in:" . implode(',', array_map('addslashes', $options));
                        } else {
                            // Single checkbox
                            $validationRules[$fieldKey] = $isRequired ? 'required|accepted' : 'nullable|boolean';
                        }
                        break;
                    case 'select':
                    case 'radio':
                        // Validate against available options
                        $options = $field['options'] ?? [];
                        if (!empty($options)) {
                            $validationRules[$fieldKey] = $isRequired 
                                ? "required|in:" . implode(',', array_map('addslashes', $options))
                                : "nullable|in:" . implode(',', array_map('addslashes', $options));
                        } else {
                            $validationRules[$fieldKey] = "{$baseRule}|string|max:500";
                        }
                        break;
                    default:
                        $validationRules[$fieldKey] = "{$baseRule}|string|max:500";
                        break;
                }
            }
        }

        $validated = $request->validate($validationRules);

        $sourceFromRequest = $request->query('source') ?? ($validated['source'] ?? 'custom_product');

        // Validate MAC address with HotPlayer API for activation products
        if ($product->product_type === 'hotplayer_activation') {
            $macAddress = $validated['mac_address'];
            
            // Validate MAC format
            if (!HotPlayerService::isValidMacFormat($macAddress)) {
                return back()->withInput()->withErrors([
                    'mac_address' => 'Invalid MAC address format. Use format: XX:XX:XX:XX:XX:XX'
                ]);
            }

            // Check device with HotPlayer API
            $hotPlayerService = HotPlayerService::forSource($sourceFromRequest);
            $deviceCheck = $hotPlayerService->checkDevice($macAddress);

            if (!$deviceCheck['success']) {
                return back()->withInput()->withErrors([
                    'mac_address' => $deviceCheck['error'] ?? 'Failed to verify device with HotPlayer'
                ]);
            }

            // Check if device already has lifetime activation
            if (isset($deviceCheck['plan']) && $deviceCheck['plan'] === 'FOREVER') {
                return back()->withInput()->withErrors([
                    'mac_address' => 'This device is already activated with a Lifetime plan'
                ]);
            }
        }

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

        // Update user information if needed (name, phone)
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

        // Create a payment intent for the custom product
        $orderData = [
            'user_id' => $user->id,
            'custom_product_id' => $product->id,
            'source' => $sourceFromRequest,
            'order_type' => 'custom_product',
            'customer' => [
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ],
            'custom_fields' => $request->has('custom_fields') ? $request->custom_fields : [],
        ];

        // Add MAC address for HotPlayer activation products
        if ($product->product_type === 'hotplayer_activation' && isset($validated['mac_address'])) {
            $orderData['mac_address'] = $validated['mac_address'];
            $orderData['activation_plan'] = $product->metadata['hotplayer_plan'] ?? 'YEAR_1';
            $orderData['hotplayer_device_info'] = $deviceCheck['data'] ?? null;
        }

        $paymentIntent = PaymentIntent::create([
            'user_id' => $user->id,
            'pricing_plan_id' => null, // No pricing plan for custom products
            'payment_intent_id' => 'pi_temp_' . uniqid(),
            'payment_method' => $validated['payment_method'],
            'amount' => $product->price,
            'currency' => 'USD',
            'status' => 'pending',
            'order_type' => 'subscription', // Use 'subscription' as it's allowed in the ENUM constraint
            'order_data' => $orderData,
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

