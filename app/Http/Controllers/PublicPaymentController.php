<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentIntent;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderClientMail;
use App\Mail\NewOrderAdminMail;
use App\Models\User;

class PublicPaymentController extends Controller
{
    public function stripePayment(PaymentIntent $paymentIntent)
    {
        // Load appropriate relationships based on order type
        if ($paymentIntent->order_type === 'credit_pack') {
            $paymentIntent->load(['resellerCreditPack', 'user']);
        } else {
            $paymentIntent->load(['pricingPlan', 'user']);
        }

        if ($paymentIntent->isExpired() || !$paymentIntent->isPending()) {
            // Redirect based on order type
            if (isset($paymentIntent->order_data['custom_product_id'])) {
                $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
                return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Payment session expired. Please try again.');
            } elseif ($paymentIntent->order_type === 'credit_pack') {
                return redirect()->route('reseller.checkout.show')->with('error', 'Payment session expired. Please try again.');
            } else {
                return redirect()->route('checkout.show', ['plan_id' => $paymentIntent->pricing_plan_id])->with('error', 'Payment session expired. Please try again.');
            }
        }

        $stripePublicKey = SystemSetting::get('stripe_public_key', '');
        $stripeSecretKey = SystemSetting::get('stripe_secret_key', '');

        if (empty($stripePublicKey) || empty($stripeSecretKey)) {
            return redirect()->route('checkout.show')->with('error', 'Stripe not configured.');
        }

        // Create a real Stripe payment intent via PaymentService
        [$stripePaymentIntent, $clientSecret] = \App\Services\PaymentService::createStripePaymentIntent($paymentIntent);

        // Load custom product if applicable
        if (isset($paymentIntent->order_data['custom_product_id'])) {
            $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
            return view('public.payment.stripe', compact('paymentIntent', 'stripePaymentIntent', 'customProduct'));
        }

        return view('public.payment.stripe', compact('paymentIntent', 'stripePaymentIntent'));
    }

    public function paypalPayment(PaymentIntent $paymentIntent)
    {
        // Load appropriate relationships based on order type
        if ($paymentIntent->order_type === 'credit_pack') {
            $paymentIntent->load(['resellerCreditPack', 'user']);
        } else {
            $paymentIntent->load(['pricingPlan', 'user']);
        }
        
        if ($paymentIntent->isExpired() || !$paymentIntent->isPending()) {
            // Redirect based on order type
            if (isset($paymentIntent->order_data['custom_product_id'])) {
                $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
                return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Payment session expired. Please try again.');
            } elseif ($paymentIntent->order_type === 'credit_pack') {
                return redirect()->route('reseller.checkout.show')->with('error', 'Payment session expired. Please try again.');
            } else {
                return redirect()->route('checkout.show', ['plan_id' => $paymentIntent->pricing_plan_id])->with('error', 'Payment session expired. Please try again.');
            }
        }

        // Load custom product if applicable
        if (isset($paymentIntent->order_data['custom_product_id'])) {
            $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
            return view('public.payment.paypal', compact('paymentIntent', 'customProduct'));
        }

        return view('public.payment.paypal', compact('paymentIntent'));
    }

    public function cryptoPayment(PaymentIntent $paymentIntent)
    {
        // Load appropriate relationships based on order type
        if ($paymentIntent->order_type === 'credit_pack') {
            $paymentIntent->load(['resellerCreditPack', 'user']);
        } else {
            $paymentIntent->load(['pricingPlan', 'user']);
        }
        
        if ($paymentIntent->isExpired() || !$paymentIntent->isPending()) {
            // Redirect based on order type
            if (isset($paymentIntent->order_data['custom_product_id'])) {
                $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
                return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Payment session expired. Please try again.');
            } elseif ($paymentIntent->order_type === 'credit_pack') {
                return redirect()->route('reseller.checkout.show')->with('error', 'Payment session expired. Please try again.');
            } else {
                return redirect()->route('checkout.show', ['plan_id' => $paymentIntent->pricing_plan_id])->with('error', 'Payment session expired. Please try again.');
            }
        }

        // Load custom product if applicable
        if (isset($paymentIntent->order_data['custom_product_id'])) {
            $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
            return view('public.payment.crypto', compact('paymentIntent', 'customProduct'));
        }

        return view('public.payment.crypto', compact('paymentIntent'));
    }

    public function coinbaseCommercePayment(Request $request, PaymentIntent $paymentIntent)
    {

        // Load appropriate relationships based on order type
        if ($paymentIntent->order_type === 'credit_pack') {
            $paymentIntent->load(['resellerCreditPack', 'user']);
        } else {
            $paymentIntent->load(['pricingPlan', 'user']);
        }
        
        if ($paymentIntent->isExpired() || !$paymentIntent->isPending()) {
            \Log::warning('Coinbase Commerce payment: Payment intent expired or not pending', [
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'expires_at' => $paymentIntent->expires_at,
                'is_expired' => $paymentIntent->isExpired(),
                'is_pending' => $paymentIntent->isPending(),
            ]);

            // Redirect based on order type
            if (isset($paymentIntent->order_data['custom_product_id'])) {
                $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
                return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Payment session expired. Please try again.');
            } elseif ($paymentIntent->order_type === 'credit_pack') {
                return redirect()->route('reseller.checkout.show')->with('error', 'Payment session expired. Please try again.');
            } else {
                return redirect()->route('checkout.show', ['plan_id' => $paymentIntent->pricing_plan_id])->with('error', 'Payment session expired. Please try again.');
            }
        }

        try {
            // Check if payment intent already has a charge (idempotency)
            // If user goes back or page reloads, reuse existing charge instead of creating new one
            $hostedUrl = null;
            
            if (!empty($paymentIntent->payment_intent_id) && !empty($paymentIntent->gateway_response)) {
                // Payment already initialized - reuse existing charge
                $existingCharge = null;
                $gatewayResponse = $paymentIntent->gateway_response;
                
                if (is_array($gatewayResponse)) {
                    $existingCharge = $gatewayResponse;
                } elseif (is_string($gatewayResponse)) {
                    $decoded = json_decode($gatewayResponse, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $existingCharge = $decoded;
                    }
                }
                
                if (is_array($existingCharge) && isset($existingCharge['hosted_url'])) {
                    $hostedUrl = $existingCharge['hosted_url'];
                    \Log::info('Coinbase Commerce: Reusing existing charge', [
                        'payment_intent_id' => $paymentIntent->id,
                        'charge_id' => $paymentIntent->payment_intent_id,
                    ]);
                }
            }
            
            // Only create new charge if we don't have an existing one
            if (!$hostedUrl) {
                // Create Coinbase Commerce charge
                $chargeData = \App\Services\PaymentService::createCoinbaseCommerceCharge($paymentIntent);
                
                // Store charge ID in payment intent for webhook verification
                $paymentIntent->update([
                    'payment_intent_id' => $chargeData['charge_id'],
                    'gateway_response' => $chargeData['charge'],
                ]);
                
                $hostedUrl = $chargeData['hosted_url'];
                \Log::info('Coinbase Commerce: Created new charge', [
                    'payment_intent_id' => $paymentIntent->id,
                    'charge_id' => $chargeData['charge_id'],
                ]);
            }

            // Redirect to Coinbase Commerce payment link
            // Coinbase Commerce cannot be loaded in iframes due to CSP restrictions
            // ALWAYS use break-out page to ensure payment link opens in top window (_top)
            
            // Always use the break-out page for Coinbase Commerce
            // This ensures the Coinbase payment link opens in the top window, not the iframe
            // The break-out page uses document.write with form target="_top" to break out
            return response()->view('public.payment.coinbase-commerce-redirect', [
                'paymentIntent' => $paymentIntent,
                'hostedUrl' => $hostedUrl,
            ])->header('X-Frame-Options', 'SAMEORIGIN');
        } catch (\Exception $e) {
            \Log::error('Coinbase Commerce payment error', [
                'payment_intent_id' => $paymentIntent->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'payment_intent_data' => [
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'order_type' => $paymentIntent->order_type,
                    'status' => $paymentIntent->status,
                    'pricing_plan_id' => $paymentIntent->pricing_plan_id,
                ],
            ]);

            // Redirect based on order type
            if (isset($paymentIntent->order_data['custom_product_id'])) {
                $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
                return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Failed to initialize payment. Please try again.');
            } elseif ($paymentIntent->order_type === 'credit_pack') {
                return redirect()->route('reseller.checkout.show')->with('error', 'Failed to initialize payment. Please try again.');
            } else {
                return redirect()->route('checkout.show', ['plan_id' => $paymentIntent->pricing_plan_id])->with('error', 'Failed to initialize payment. Please try again.');
            }
        }
    }

    public function coinbaseCommerceSuccess(Request $request, PaymentIntent $paymentIntent)
    {
        // Coinbase Commerce redirects here after payment
        // The actual payment confirmation comes via webhook
        // Check if payment was already processed
        if ($paymentIntent->isCompleted()) {
            $order = \App\Models\Order::where('payment_id', $paymentIntent->payment_intent_id)->latest()->first();
            if ($order) {
                return redirect()->route('public.thank-you', $order);
            }
        }

        // If not completed yet, show pending message
        return view('public.payment.coinbase-commerce-pending', compact('paymentIntent'));
    }

    /**
     * Check Coinbase Commerce payment status
     * Called by the pending page to poll for payment completion
     */
    public function coinbaseCommerceCheckStatus(PaymentIntent $paymentIntent)
    {
        // First, check if an order already exists for this payment intent (idempotency check)
        $existingOrder = \App\Models\Order::where('payment_id', $paymentIntent->payment_intent_id)->first();
        if ($existingOrder) {
            return response()->json([
                'status' => 'completed',
                'order_id' => $existingOrder->id,
                'order_number' => $existingOrder->order_number,
                'redirect_url' => route('public.thank-you', $existingOrder),
            ]);
        }

        // If no charge code stored, payment wasn't initialized properly
        if (empty($paymentIntent->payment_intent_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not initialized',
            ], 400);
        }

        // Check status from Coinbase Commerce API
        $chargeData = \App\Services\PaymentService::getCoinbaseCommerceChargeStatus($paymentIntent->payment_intent_id);
        
        if (!$chargeData) {
            return response()->json([
                'status' => 'pending',
                'message' => 'Unable to check payment status',
            ]);
        }

        $chargeStatus = strtoupper($chargeData['timeline'] ? (end($chargeData['timeline'])['status'] ?? 'NEW') : ($chargeData['status'] ?? 'NEW'));

        // Create order when payment is PENDING (detected on blockchain) or COMPLETED/RESOLVED
        // PENDING means payment detected on blockchain, funds received, waiting for confirmations
        if (in_array($chargeStatus, ['PENDING', 'COMPLETED', 'RESOLVED'])) {
            // Double-check no order exists (race condition protection)
            $existingOrder = \App\Models\Order::where('payment_id', $paymentIntent->payment_intent_id)->first();
            if ($existingOrder) {
                return response()->json([
                    'status' => $chargeStatus === 'PENDING' ? 'pending' : 'completed',
                    'order_id' => $existingOrder->id,
                    'order_number' => $existingOrder->order_number,
                    'redirect_url' => route('public.thank-you', $existingOrder),
                ]);
            }

            // Update payment intent status - mark as completed when payment is detected (PENDING or higher)
            if (!$paymentIntent->isCompleted()) {
                $paymentIntent->update([
                    'status' => 'completed',
                    'gateway_response' => $chargeData,
                    'completed_at' => now(),
                ]);
            }

            // Only create order if payment intent is not already processed
            if ($paymentIntent->status !== 'processed') {
                try {
                    // Create order and payment records
                    $order = $paymentIntent->createOrder();

                    // Dispatch PaymentCompleted event to send emails
                    try {
                        \App\Events\PaymentCompleted::dispatch($order, $paymentIntent);
                    } catch (\Throwable $e) {
                        \Log::error('Failed to dispatch PaymentCompleted event', [
                            'order_id' => $order->id ?? null,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    return response()->json([
                        'status' => $chargeStatus === 'PENDING' ? 'pending' : 'completed',
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'redirect_url' => route('public.thank-you', $order),
                    ]);
                } catch (\Throwable $e) {
                    // If order creation fails (e.g., already processed), try to find existing order
                    $existingOrder = \App\Models\Order::where('payment_id', $paymentIntent->payment_intent_id)->first();
                    if ($existingOrder) {
                        return response()->json([
                            'status' => $chargeStatus === 'PENDING' ? 'pending' : 'completed',
                            'order_id' => $existingOrder->id,
                            'order_number' => $existingOrder->order_number,
                            'redirect_url' => route('public.thank-you', $existingOrder),
                        ]);
                    }

                    \Log::error('Failed to process Coinbase Commerce payment', [
                        'payment_intent_id' => $paymentIntent->id,
                        'charge_status' => $chargeStatus,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to process payment',
                    ], 500);
                }
            } else {
                // Payment intent already processed, find the order
                $order = \App\Models\Order::where('payment_id', $paymentIntent->payment_intent_id)->first();
                if ($order) {
                    return response()->json([
                        'status' => $chargeStatus === 'PENDING' ? 'pending' : 'completed',
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'redirect_url' => route('public.thank-you', $order),
                    ]);
                }
            }
        }

        // Handle failed/expired/canceled status
        if (in_array($chargeStatus, ['FAILED', 'EXPIRED', 'CANCELED'])) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment ' . strtolower($chargeStatus),
            ]);
        }

        // Still pending
        return response()->json([
            'status' => 'pending',
            'charge_status' => $chargeStatus,
        ]);
    }

    public function coinbaseCommerceCancel(Request $request, PaymentIntent $paymentIntent)
    {
        // User cancelled payment on Coinbase Commerce
        // Redirect based on order type
        if (isset($paymentIntent->order_data['custom_product_id'])) {
            $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
            return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Payment was cancelled.');
        } elseif ($paymentIntent->order_type === 'credit_pack') {
            return redirect()->route('reseller.checkout.show')->with('error', 'Payment was cancelled.');
        } else {
            return redirect()->route('checkout.show', ['plan_id' => $paymentIntent->pricing_plan_id])->with('error', 'Payment was cancelled.');
        }
    }

    public function coinbaseCommerceWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-CC-Webhook-Signature');
        $webhookSecret = \App\Services\PaymentService::getCoinbaseCommerceWebhookSecret();

        if (empty($webhookSecret)) {
            \Log::error('Coinbase Commerce webhook secret not configured');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        // Verify webhook signature (Coinbase Commerce sends signature in format: timestamp=signature)
        if (!empty($signature)) {
            // Parse the signature header (format: timestamp=signature)
            $parts = explode('=', $signature, 2);
            if (count($parts) === 2) {
                $timestamp = $parts[0];
                $receivedSignature = $parts[1];
                
                // Compute expected signature
                $signedPayload = $timestamp . '.' . $payload;
                $computedSignature = hash_hmac('sha256', $signedPayload, $webhookSecret);
                
                if (!hash_equals($receivedSignature, $computedSignature)) {
                    \Log::error('Coinbase Commerce webhook signature verification failed', [
                        'received' => $receivedSignature,
                        'computed' => $computedSignature,
                    ]);
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
            } else {
                // Fallback: try direct comparison (older format)
                $computedSignature = hash_hmac('sha256', $payload, $webhookSecret);
                if (!hash_equals($signature, $computedSignature)) {
                    \Log::error('Coinbase Commerce webhook signature verification failed', [
                        'received' => $signature,
                        'computed' => $computedSignature,
                    ]);
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
            }
        } else {
            \Log::error('Coinbase Commerce webhook missing signature header');
            return response()->json(['error' => 'Missing signature'], 401);
        }

        $event = json_decode($payload, true);
        $eventType = $event['type'] ?? '';
        $charge = $event['data'] ?? [];


        // Find payment intent by charge code
        $chargeCode = $charge['code'] ?? '';
        if (empty($chargeCode)) {
            \Log::error('Coinbase Commerce webhook missing charge code');
            return response()->json(['error' => 'Missing charge code'], 400);
        }

        $paymentIntent = PaymentIntent::where('payment_intent_id', $chargeCode)->first();
        if (!$paymentIntent) {
            \Log::error('Coinbase Commerce webhook: Payment intent not found', [
                'charge_code' => $chargeCode,
            ]);
            return response()->json(['error' => 'Payment intent not found'], 404);
        }

        // Get the current charge status from the charge data
        // Coinbase Commerce provides status in timeline array or directly in status field
        $chargeStatus = null;
        if (isset($charge['timeline']) && is_array($charge['timeline']) && !empty($charge['timeline'])) {
            // Get the latest status from timeline
            $latestTimeline = end($charge['timeline']);
            $chargeStatus = strtoupper($latestTimeline['status'] ?? '');
        } elseif (isset($charge['status'])) {
            $chargeStatus = strtoupper($charge['status']);
        }


        // Handle different event types
        switch ($eventType) {
            case 'charge:confirmed':
            case 'charge:resolved':
            case 'charge:pending':
                // Payment detected on blockchain (PENDING) or confirmed (COMPLETED/RESOLVED)
                // Create order when payment is PENDING (detected on blockchain) or higher
                if (in_array($chargeStatus, ['PENDING', 'COMPLETED', 'RESOLVED'])) {
                    // First check if order already exists (idempotency)
                    $existingOrder = \App\Models\Order::where('payment_id', $chargeCode)->first();
                    if ($existingOrder) {
                        break;
                    }

                    // Only process if payment intent is not already completed/processed
                    if (!$paymentIntent->isCompleted() && $paymentIntent->status !== 'processed') {
                        try {
                            $paymentIntent->update([
                                'status' => 'completed',
                                'gateway_response' => $charge,
                                'completed_at' => now(),
                            ]);

                            // Double-check no order exists (race condition protection)
                            $existingOrder = \App\Models\Order::where('payment_id', $chargeCode)->first();
                            if ($existingOrder) {
                                break;
                            }

                            // Create order and payment records
                            $order = $paymentIntent->createOrder();

                            // Dispatch PaymentCompleted event to send emails
                            try {
                                \App\Events\PaymentCompleted::dispatch($order, $paymentIntent);
                            } catch (\Throwable $e) {
                                \Log::error('Failed to dispatch PaymentCompleted event in webhook', [
                                    'order_id' => $order->id ?? null,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        } catch (\Throwable $e) {
                            // If order creation fails, check if order was created by another process
                            $existingOrder = \App\Models\Order::where('payment_id', $chargeCode)->first();
                            if ($existingOrder) {
                                // Order was created by another process, continue
                            } else {
                                \Log::error('Failed to process Coinbase Commerce webhook payment', [
                                    'payment_intent_id' => $paymentIntent->id,
                                    'charge_code' => $chargeCode,
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString(),
                                ]);
                            }
                        }
                    }
                }
                break;

            case 'charge:failed':
            case 'charge:delayed':
            case 'charge:expired':
            case 'charge:canceled':
                // Payment failed, delayed, expired, or canceled
                \Log::warning('Coinbase Commerce payment not successful', [
                    'payment_intent_id' => $paymentIntent->id,
                    'charge_code' => $chargeCode,
                    'event_type' => $eventType,
                ]);
                break;


            default:
                // Unhandled event type - log for monitoring
                \Log::info('Coinbase Commerce webhook received unhandled event type', [
                    'event_type' => $eventType,
                    'charge_code' => $chargeCode,
                ]);
                break;
        }

        // Always return 200 OK to acknowledge webhook receipt
        // Coinbase Commerce will retry if we don't return 200
        return response()->json(['status' => 'received'], 200);
    }

    public function paymentIntentSuccess(Request $request, PaymentIntent $paymentIntent)
    {

        // If already completed/processed, idempotently redirect to thank-you (handles GET access/bookmarks)
        if ($paymentIntent->isCompleted() || $paymentIntent->status === 'processed') {
            // Try to find an existing order by the intent's transaction id
            $existingOrder = \App\Models\Order::where('payment_id', $paymentIntent->payment_intent_id)->latest()->first();
            if ($existingOrder) {
                return redirect()->route('public.thank-you', $existingOrder);
            }
            // If completed but order missing (rare), try to create it now
            if ($paymentIntent->isCompleted()) {
                try {
                    $order = $paymentIntent->createOrder();
                    return redirect()->route('public.thank-you', $order);
                } catch (\Throwable $e) {
                    \Log::error('Idempotent order creation failed', [
                        'payment_intent_id' => $paymentIntent->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            // Fallback
            return redirect()->route('pricing')->with('warning', 'Payment already processed.');
        }

        $validated = $request->validate([
            'payment_id' => 'required|string',
            'payment_method' => 'required|in:stripe,paypal,crypto,coinbase_commerce',
            'payment_details' => 'nullable',
            'order_id' => 'nullable|string', // PayPal order ID
        ]);

        if ($paymentIntent->isExpired() || !$paymentIntent->isPending()) {
            // For custom products, redirect to custom product checkout
            if (isset($paymentIntent->order_data['custom_product_id'])) {
                $customProduct = \App\Models\CustomProduct::find($paymentIntent->order_data['custom_product_id']);
                return redirect()->route('custom-product.checkout.show', $customProduct->slug)->with('error', 'Payment session expired. Please try again.');
            }
            
            // For regular subscriptions, redirect to pricing page
            return redirect()->route('pricing')->with('error', 'Payment session expired. Please try again.');
        }

        // Handle PayPal order capture
        if ($validated['payment_method'] === 'paypal' && isset($validated['order_id'])) {
            \Log::info('Processing PayPal order capture', [
                'order_id' => $validated['order_id'],
                'payment_id' => $validated['payment_id']
            ]);
            
            try {
                $paypalOrderId = $validated['order_id'];
                
                // Capture PayPal order using PayPal API
                $captureResult = $this->capturePayPalOrder($paypalOrderId);
                
                \Log::info('PayPal capture result', $captureResult);
                
                if (!$captureResult['success']) {
                    \Log::error('PayPal capture failed', $captureResult);
                    return redirect()->back()
                        ->withErrors(['payment_id' => 'Failed to capture PayPal payment: ' . $captureResult['error']])
                        ->withInput();
                }
                
                // Update payment_id with captured transaction ID
                $validated['payment_id'] = $captureResult['transaction_id'];
                $validated['payment_details'] = json_encode($captureResult['details']);
                
                \Log::info('PayPal capture successful', [
                    'transaction_id' => $captureResult['transaction_id']
                ]);
                
            } catch (\Exception $e) {
                \Log::error('PayPal capture error: ' . $e->getMessage());
                return redirect()->back()
                    ->withErrors(['payment_id' => 'Failed to process PayPal payment. Please try again.'])
                    ->withInput();
            }
        }

        // For crypto, enforce unique TXID across both payments and payment intents
        if ($validated['payment_method'] === 'crypto') {
            $txid = $validated['payment_id'];
            $txidExists = Payment::where('payment_id', $txid)->exists()
                || PaymentIntent::where('payment_intent_id', $txid)->exists();

            if ($txidExists) {
                return redirect()->back()
                    ->withErrors(['payment_id' => 'This Transaction ID (TXID) already exists. Please provide a valid, unique TXID.'])
                    ->withInput();
            }
        }

        // Mark payment intent completed
        $paymentIntent->update([
            'status' => 'completed',
            'payment_intent_id' => $validated['payment_id'],
            'gateway_response' => json_decode($validated['payment_details'] ?? '[]', true),
            'completed_at' => now(),
        ]);

        // Create order and payment records from intent AFTER payment success
        $order = $paymentIntent->createOrder();

        // Dispatch PaymentCompleted event to send emails immediately (avoids duplicates)
        try {
            \App\Events\PaymentCompleted::dispatch($order, $paymentIntent);
        } catch (\Throwable $e) {
            \Log::error('Failed to dispatch PaymentCompleted event', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        // Redirect to public thank-you page
        return redirect()->route('public.thank-you', $order);
    }

    /**
     * Public thank-you page after successful payment
     */
    public function thankYou(Order $order)
    {
        $order->load(['pricingPlan', 'customProduct']);

        // Compute a dynamic return URL based on the order's source
        $returnUrl = config('app.url');

        try {
            $sourceName = trim((string) ($order->source ?? ''));

            if (!empty($sourceName)) {
                // If a Source model and table exist, try to resolve the URL from there
                if (class_exists('App\\Models\\Source') && \Illuminate\Support\Facades\Schema::hasTable('sources')) {
                    $sourceModel = \App\Models\Source::where('name', $sourceName)->first();
                    if (!empty($sourceModel?->return_url)) {
                        $returnUrl = $sourceModel->return_url;
                    }
                }

                // If the source field itself looks like a full URL, use it as a fallback
                if ($returnUrl === config('app.url') && filter_var($sourceName, FILTER_VALIDATE_URL)) {
                    $returnUrl = $sourceName;
                }
            }
        } catch (\Throwable $e) {
            // Fall back silently to app URL if anything goes wrong
            $returnUrl = config('app.url');
        }

        return view('public.thank-you', compact('order', 'returnUrl'));
    }

    /**
     * Capture PayPal order
     */
    private function capturePayPalOrder($orderId)
    {
        try {
            $paypalMode = \App\Models\SystemSetting::get('paypal_mode', 'sandbox');
            $clientId = \App\Services\PaymentService::getPayPalClientId();
            $clientSecret = \App\Services\PaymentService::getPayPalClientSecret();
            
            \Log::info('PayPal capture attempt', [
                'order_id' => $orderId,
                'mode' => $paypalMode,
                'client_id' => $clientId,
                'has_client_secret' => !empty($clientSecret)
            ]);
            
            $baseUrl = $paypalMode === 'sandbox' 
                ? 'https://api.sandbox.paypal.com' 
                : 'https://api.paypal.com';
            
            // Get access token
            $tokenResponse = $this->getPayPalAccessToken($baseUrl, $clientId, $clientSecret);
            
            if (!$tokenResponse['success']) {
                \Log::error('PayPal access token failed', $tokenResponse);
                return ['success' => false, 'error' => 'Failed to get PayPal access token'];
            }
            
            $accessToken = $tokenResponse['access_token'];
            
            // Capture the order
            $captureResponse = $this->capturePayPalOrderWithToken($baseUrl, $orderId, $accessToken);
            
            return $captureResponse;
            
        } catch (\Exception $e) {
            \Log::error('PayPal capture error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get PayPal access token
     */
    private function getPayPalAccessToken($baseUrl, $clientId, $clientSecret)
    {
        try {
            $response = \Http::withBasicAuth($clientId, $clientSecret)
                ->withOptions([
                    'verify' => app()->environment('local') ? false : true,
                    'timeout' => 30,
                ])
                ->asForm()
                ->post($baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'access_token' => $data['access_token']
                ];
            }
            
            \Log::error('PayPal access token request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);
            
            return ['success' => false, 'error' => 'Failed to get access token: HTTP ' . $response->status()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Capture PayPal order with access token
     */
    private function capturePayPalOrderWithToken($baseUrl, $orderId, $accessToken)
    {
        try {
            $response = \Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal',
            ])->withOptions([
                'verify' => app()->environment('local') ? false : true,
                'timeout' => 30,
            ])->withBody('{}', 'application/json')
            ->post($baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'COMPLETED') {
                    $capture = $data['purchase_units'][0]['payments']['captures'][0];
                    
                    \Log::info('PayPal order captured successfully', [
                        'order_id' => $orderId,
                        'transaction_id' => $capture['id'],
                        'status' => $data['status']
                    ]);
                    
                    return [
                        'success' => true,
                        'transaction_id' => $capture['id'],
                        'details' => $data
                    ];
                }
                
                \Log::warning('PayPal order not completed', [
                    'order_id' => $orderId,
                    'status' => $data['status'] ?? 'unknown',
                    'response' => $data
                ]);
                
                return ['success' => false, 'error' => 'Order not completed: ' . ($data['status'] ?? 'unknown status')];
            }
            
            \Log::error('PayPal capture request failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);
            
            return ['success' => false, 'error' => 'Failed to capture order: HTTP ' . $response->status()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}


