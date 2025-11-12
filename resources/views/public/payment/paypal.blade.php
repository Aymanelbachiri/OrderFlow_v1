@extends('layouts.checkout')

@section('title', 'PayPal Payment')

@section('content')
<div class="min-h-screen flex items-center py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in-up">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">PayPal Payment</h1>
            <p class="text-gray-900 dark:text-white/60 text-lg">Fast and secure payment with PayPal</p>
            <div class="flex items-center justify-center space-x-2 mt-4">
                <div class="flex items-center space-x-1 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>PayPal Protected</span>
                </div>
                <div class="text-gray-900 dark:text-white/40">•</div>
                <span class="text-gray-900 dark:text-white/60 text-sm">Payment ID: #{{ $paymentIntent->id }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-[#e5e7eb] dark:border-gray-700 shadow-md sticky top-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="px-6 py-5 border-b border-[#e5e7eb] dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Order Summary</h2>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Payment Breakdown -->
                        <h3 class="text-2xl font-bold text-center py-4 text-gray-900 dark:text-white mb-2">
                            @if(isset($customProduct))
                                {{ $customProduct->name }}
                            @else
                                {{ $paymentIntent->pricingPlan->display_name ?? $paymentIntent->resellerCreditPack->name ?? 'Order' }}
                            @endif
                        </h3>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Payment Breakdown</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 dark:text-white/70">Subtotal:</span>
                                    <span class="text-gray-900 dark:text-white">${{ number_format($paymentIntent->amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-900 dark:text-white/70">PayPal Fee:</span>
                                    <span class="text-green-600 font-medium">$0.00</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between font-semibold">
                                    <span class="text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-xl text-blue-500">${{ number_format($paymentIntent->amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal Benefits -->
                        <div class="bg-blue-50 border border-[#e5e7eb] rounded-lg p-4">
                            <h4 class="font-semibold text-blue-700 text-sm mb-2">PayPal Benefits</h4>
                            <div class="space-y-2 text-xs text-blue-600">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Buyer Protection</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>No card details shared</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Fast & Secure</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PayPal Payment Section -->
            <div class="lg:col-span-3 order-1 lg:order-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-[#e5e7eb] dark:border-gray-700 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="px-6 py-5 border-b border-[#e5e7eb] dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="blue" class="bi bi-paypal" viewBox="0 0 16 16">
                                    <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                  </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">PayPal Payment</h2>
                        </div>
                    </div>

                    <div class="p-6">
                        @php $paypalClientId = \App\Services\PaymentService::getPayPalClientId(); @endphp
                        @if(empty($paypalClientId))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-red-700 mb-2">PayPal Unavailable</h3>
                                <p class="text-red-600">PayPal is not configured. Please contact our support team for assistance.</p>
                            </div>
                        @else
                            <!-- PayPal Information -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6 border border-[#e5e7eb]">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-blue-300 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-paypal" viewBox="0 0 16 16">
                                            <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                          </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Pay with PayPal</h3>
                                        <p class="text-blue-700 text-sm mb-3">Click the button below to securely complete your payment through PayPal. You can use your PayPal balance, bank account, or any card linked to your PayPal account.</p>
                                        <div class="flex items-center space-x-4 text-xs text-blue-600">
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Secure</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Fast</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Protected</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal Button Container -->
                            <div class="bg-white  p-6 rounded-lg border border-gray-200 mb-6">
                                <h4 class="font-semibold text-gray-900 text-xl mb-4 text-center">Complete Your Payment</h4>
                                <div id="paypal-button-container" class="min-h-[50px]">
                                    <!-- PayPal button will render here -->
                                </div>
                                
                            </div>

                            <!-- Alternative Payment Notice -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-amber-700 text-sm mb-1">Don't have PayPal?</h4>
                                        <p class="text-amber-600 text-sm">You can still pay with your credit or debit card through PayPal's secure checkout - no PayPal account required.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

/* Custom spinner animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

@if(!empty($paypalClientId))
<!-- PayPal SDK -->
@php
    $paypalMode = \App\Models\SystemSetting::get('paypal_mode', 'sandbox');
    
    // Enhanced SDK URL with proper parameters for card fields support
    $sdkUrl = $paypalMode === 'sandbox' 
        ? "https://www.sandbox.paypal.com/sdk/js?client-id={$paypalClientId}&currency=USD&intent=capture&components=buttons,card-fields&enable-funding=paypal,card"
        : "https://www.paypal.com/sdk/js?client-id={$paypalClientId}&currency=USD&intent=capture&components=buttons,card-fields&enable-funding=paypal,card";
@endphp

<script src="{{ $sdkUrl }}" defer></script>

<!-- Loading indicator -->
<div id="paypal-loading" class="text-center py-8">
    <div class="inline-flex items-center space-x-3 text-blue-600">
        <div class="w-6 h-6 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
        <span class="font-medium">Loading PayPal...</span>
    </div>
    <p class="text-sm text-gray-900 dark:text-white/60 mt-2">Please wait while we prepare your payment</p>
    <div class="mt-4">
        <p class="text-xs text-gray-500">If PayPal doesn't load, try:</p>
        <div class="mt-2 space-x-2">
            <button onclick="location.reload()" class="text-blue-600 hover:text-blue-800 text-sm underline">Refresh Page</button>
            <span class="text-gray-400">•</span>
            @if(isset($customProduct))
                <a href="{{ route('custom-product.checkout.show', $customProduct->slug) }}" class="text-gray-600 hover:text-gray-800 text-sm underline">Back to Checkout</a>
            @else
                <a href="{{ route('reseller.checkout.show') }}" class="text-gray-600 hover:text-gray-800 text-sm underline">Back to Checkout</a>
            @endif
        </div>
    </div>
</div>

<script>
// Enhanced PayPal error handling and debugging
let paypalLoadTimeout;
let paypalLoadAttempts = 0;
const maxLoadAttempts = 3;

// Global error handler for PayPal issues
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error, e.message, e.filename, e.lineno);
    
    // Check for PayPal-related errors
    const errorMessage = e.message ? e.message.toLowerCase() : '';
    const errorSource = e.filename ? e.filename.toLowerCase() : '';
    
    if (errorMessage.includes('paypal') || errorSource.includes('paypal') || 
        errorMessage.includes('paypal') || errorSource.includes('sandbox.paypal') ||
        errorSource.includes('xoplatform') || errorMessage.includes('card-fields')) {
        console.error('PayPal-related error detected:', e);
        
        // Don't show error for logger API failures - these are non-critical
        if (errorSource.includes('xoplatform') && errorMessage.includes('logger')) {
            console.warn('PayPal logger API error (non-critical):', e.message);
            return;
        }
        
        handlePayPalError('JavaScript error: ' + e.message);
    }
});

// Handle unhandled promise rejections
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
    
    if (e.reason && typeof e.reason === 'string' && 
        (e.reason.toLowerCase().includes('paypal') || e.reason.toLowerCase().includes('card-fields'))) {
        console.error('PayPal promise rejection detected:', e.reason);
        handlePayPalError('PayPal error: ' + e.reason);
    }
});

// Handle PayPal errors
function handlePayPalError(message) {
    const loading = document.getElementById('paypal-loading');
    if (loading && loading.style.display !== 'none') {
        loading.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-red-700 mb-2">PayPal Error</h3>
                <p class="text-red-600 text-sm mb-4">${message}</p>
                <div class="mt-4 space-x-2">
                    <button onclick="location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Try Again
                    </button>
                    @if(isset($customProduct))
                        <a href="{{ route('custom-product.checkout.show', $customProduct->slug) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-block">
                            Back to Checkout
                        </a>
                    @else
                        <a href="{{ route('reseller.checkout.show') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-block">
                            Back to Checkout
                        </a>
                    @endif
                </div>
            </div>
        `;
    }
}

// Check if PayPal SDK loaded successfully
function checkPayPalSDK() {
    return typeof paypal !== 'undefined' && paypal.Buttons;
}

document.addEventListener('DOMContentLoaded', function() {
    // Set timeout for PayPal SDK loading
    paypalLoadTimeout = setTimeout(() => {
        console.error('PayPal SDK loading timeout');
        handlePayPalError('PayPal SDK failed to load within 15 seconds. This might be due to network issues or CSP restrictions.');
    }, 15000);

    // Wait for PayPal SDK to load with retry mechanism
    const initPayPal = () => {
        paypalLoadAttempts++;
        
        if (!checkPayPalSDK()) {
            if (paypalLoadAttempts < maxLoadAttempts) {
                setTimeout(initPayPal, 500);
                return;
            } else {
                console.error('PayPal SDK failed to load after maximum attempts');
                handlePayPalError('PayPal SDK failed to load. Please check your internet connection and try again.');
                return;
            }
        }

        // Clear timeout since PayPal loaded successfully
        if (paypalLoadTimeout) {
            clearTimeout(paypalLoadTimeout);
        }

        // Hide loading indicator
        const loading = document.getElementById('paypal-loading');
        if (loading) {
            loading.style.display = 'none';
        }

        try {
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'blue',
                    shape: 'rect',
                    label: 'paypal',
                    height: 45
                },
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { 
                                value: '{{ number_format($paymentIntent->amount, 2, '.', '') }}',
                                currency_code: 'USD'
                            },
                            description: 'Web Service',
                            custom_id: 'WS-{{ $paymentIntent->id }}',
                            invoice_id: 'WS-{{ $paymentIntent->id }}'
                        }]
                    }).then(function(orderId) {
                        return orderId;
                    }).catch(function(error) {
                        console.error('PayPal order creation error:', error);
                        throw error;
                    });
                },
                onApprove: function(data, actions) {
                    // Show processing state
                    const container = document.getElementById('paypal-button-container');
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <div class="inline-flex items-center space-x-3 text-green-600">
                                <div class="w-6 h-6 border-2 border-green-600 border-t-transparent rounded-full animate-spin"></div>
                                <span class="font-medium">Processing your payment...</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-white/60 mt-2">Please wait while we confirm your payment</p>
                        </div>
                    `;

                    // Submit order ID to server for capture
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("public.payment-intents.success", $paymentIntent) }}';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value='{{ csrf_token() }}'/>
                        <input type="hidden" name="payment_id" value='${data.orderID}'/>
                        <input type="hidden" name="payment_method" value='paypal'/>
                        <input type="hidden" name="order_id" value='${data.orderID}'/>
                    `;
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                onError: function(err) {
                    console.error('PayPal error:', err);
                    const container = document.getElementById('paypal-button-container');
                    container.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-red-700 mb-2">Payment Error</h3>
                            <p class="text-red-600 text-sm mb-4">There was an issue processing your payment. Please try again.</p>
                            <button onclick="location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                Try Again
                            </button>
                        </div>
                    `;
                },
                onCancel: function(data) {
                    const container = document.getElementById('paypal-button-container');
                    container.innerHTML = `
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-amber-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-amber-700 mb-2">Payment Cancelled</h3>
                            <p class="text-amber-600 text-sm mb-4">You cancelled the payment. You can try again when you're ready.</p>
                            <button onclick="location.reload()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                Try Again
                            </button>
                        </div>
                    `;
                }
            }).render('#paypal-button-container').catch(function(error) {
                console.error('PayPal render error:', error);
                document.getElementById('paypal-button-container').innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <p class="text-red-600">Unable to render PayPal buttons. Please refresh the page or contact support.</p>
                        <p class="text-red-500 text-xs mt-2">Error: ${error.message || 'Unknown error'}</p>
                    </div>
                `;
            });
            
        } catch (err) {
            console.error('PayPal initialization error:', err);
            document.getElementById('paypal-button-container').innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <p class="text-red-600">Unable to initialize PayPal. Please refresh the page or contact support.</p>
                    <p class="text-red-500 text-xs mt-2">Error: ${err.message || 'Unknown error'}</p>
                </div>
            `;
        }
    };

    // Initialize PayPal
    initPayPal();
});
</script>
@endif
@endsection