@extends('layouts.checkout')

@section('title', 'Checkout')

@section('content')
    <div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-10 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Secure Checkout
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Complete your details and choose your payment method
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order Summary -->
                <aside class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm sticky top-10 overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                <span
                                    class="inline-flex w-8 h-8 rounded-md bg-gradient-to-br from-indigo-500 to-blue-500 items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2">
                                        </path>
                                    </svg>
                                </span>
                                <span>Order Summary</span>
                            </h2>

                            @if ($plan)
                                <div class="mt-6 space-y-4">
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $plan->display_name ?? ($plan->name ?? 'Selected Plan') }}
                                                </h3>
                                                @if ($plan->duration_months)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $plan->duration_months }}
                                                        month{{ $plan->duration_months > 1 ? 's' : '' }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                                ${{ number_format($plan->price, 2) }}
                                            </div>
                                        </div>

                                        @if ($plan->features && is_array($plan->features))
                                            <ul class="mt-3 border-t border-gray-100 dark:border-gray-700 pt-3 space-y-2">
                                                @foreach (array_slice($plan->features, 0, 3) as $feature)
                                                    <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                                @if (count($plan->features) > 3)
                                                    <li class="text-sm text-gray-500">+ {{ count($plan->features) - 3 }}
                                                        more</li>
                                                @endif
                                            </ul>
                                        @endif
                                    </div>

                                    <div
                                        class="p-4 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700 rounded-xl">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-900 dark:text-gray-200">Total</span>
                                            <span
                                                class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($plan->price, 2) }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">One-time payment</p>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="mt-6 text-center bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-yellow-700">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p>No plan selected. Please return to pricing page.</p>
                                </div>
                            @endif
                        </div>

                        <div
                            class="bg-gray-100 dark:bg-gray-700 py-3 px-6 text-sm text-center text-gray-600 dark:text-gray-300">
                            Secure & encrypted checkout
                        </div>
                    </div>
                </aside>

                <!-- Right Column: Form -->
                <main class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                        @if ($errors->any())
                            <div class="mb-6 p-4 border border-red-200 bg-red-50 rounded-xl text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('checkout.submit') }}" class="space-y-4 touch-manipulation" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="source" value="{{ request('source', 'main') }}">
                            <input type="hidden" name="pricing_plan_id"
                                value="{{ $planId ?? old('pricing_plan_id', '') }}">
                            <input type="hidden" name="renewal_order_number" id="renewal_order_number" value="{{ old('renewal_order_number', '') }}">

                            <!-- Personal Info -->
                            <section>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Information</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Full Name *
                                        </label>
                                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Email Address *
                                            </label>
                                            <input type="email" name="email" value="{{ old('email') }}" required
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Phone Number *
                                            </label>
                                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Subscription Type -->
                            <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Subscription Type</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="subscription-type-card relative cursor-pointer group">
                                        <input type="radio" name="subscription_type" value="new"
                                            class="sr-only subscription-type-radio"
                                            {{ old('subscription_type', 'new') === 'new' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200 flex flex-col items-center justify-center text-center">
                                            <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">New Subscription</p>
                                            <div class="subscription-type-check hidden absolute top-3 right-3 w-6 h-6 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="subscription-type-card relative cursor-pointer group">
                                        <input type="radio" name="subscription_type" value="renewal"
                                            class="sr-only subscription-type-radio"
                                            {{ old('subscription_type') === 'renewal' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200 flex flex-col items-center justify-center text-center">
                                            <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Renewal</p>
                                            <div class="subscription-type-check hidden absolute top-3 right-3 w-6 h-6 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Renewal Subscription Selection (shown when renewal is selected) -->
                                <div id="renewal-subscription-section" class="mt-6 hidden">
                                    <div id="renewal-subscriptions-container">
                                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl">
                                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                                <strong>Note:</strong> Enter your email address above to see your active subscriptions.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Referral Code (Optional) -->
                            <section id="referral-code-section" class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Referral Code (Optional)</h2>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Have a referral code?
                                    </label>
                                    <div class="flex items-start space-x-3">
                                        <input type="text" name="referral_code" id="referral_code" 
                                            value="{{ old('referral_code', request('ref')) }}" 
                                            maxlength="12"
                                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white font-mono uppercase focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="ABC123XYZ">
                                        <button type="button" id="validate-referral-btn"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg transition-colors text-sm font-medium">
                                            Validate
                                        </button>
                                    </div>
                                    <div id="referral-validation-message" class="mt-2 text-sm"></div>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Enter a referral code if you were referred by an existing customer (new customers only)
                                    </p>
                                </div>
                            </section>

                            <!-- Payment Method -->
                            <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @foreach ($availablePaymentMethods as $method)
                                        <label class="payment-method-card relative cursor-pointer group">
                                            <input type="radio" name="payment_method" value="{{ $method['key'] ?? '' }}"
                                                class="sr-only payment-method-radio"
                                                {{ old('payment_method', $defaultPaymentMethod) === ($method['key'] ?? '') ? 'checked' : '' }}>
                                            <div
                                                class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200 flex flex-col items-center justify-center text-center">

                                                <div
                                                    class="w-12 h-12 mb-3 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">

                                                    {{-- Dynamic SVG icon based on payment method --}}
                                                    @switch($method['key'])
                                                        @case('stripe')
                                                            {{-- Credit Card Icon --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                                                                <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                                                            </svg>
                                                        @break

                                                        @case('paypal')
                                                            {{-- PayPal Icon --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                                            </svg>
                                                        @break

                                                        @case('crypto')
                                                        @case('coinbase_commerce')
                                                            {{-- Bitcoin/Crypto Icon --}}
                                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10 6V4M14 6V4M14 6H7M14 6C15.6569 6 17 7.34315 17 9C17 10.6569 15.6569 12 14 12M9 18L9 12M9 6V12M10 20V18M14 20V18M9 12H15C16.6569 12 18 13.3431 18 15C18 16.6569 16.6569 18 15 18H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        @break

                                                        @default
                                                            {{-- Generic Payment Icon --}}
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                    @endswitch
                                                </div>

                                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $method['name'] ?? ucfirst($method['key'] ?? 'method') }}
                                                </p>

                                                <div
                                                    class="payment-method-check hidden absolute top-3 right-3 w-6 h-6 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach

                                </div>
                            </section>

                            <!-- Submit -->
                            <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <button type="submit" data-custom-touch="true"
                                    class="w-full py-4 text-lg font-semibold rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white shadow-lg transition-all duration-300 flex items-center justify-center space-x-3 touch-manipulation"
                                    style="min-height: 48px;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    <span>Continue to Payment</span>
                                </button>
                            </section>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        // Mobile button fixes
        document.addEventListener('DOMContentLoaded', function() {
            // Fix submit button for mobile
            const submitBtn = document.querySelector('button[type="submit"]');
            const form = document.getElementById('checkoutForm');
            
            if (submitBtn && form) {
                // Validation function
                function validateForm() {
                    // Check if required fields are filled
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            // Remove error class after user starts typing
                            field.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                            }, { once: true });
                        }
                    });
                    
                    // Check if pricing plan is selected
                    const planId = form.querySelector('input[name="pricing_plan_id"]');
                    if (planId && !planId.value) {
                        isValid = false;
                        alert('Please select a pricing plan first.');
                    }
                    
                    return isValid;
                }
                
                // Add form validation before submit
                form.addEventListener('submit', function(e) {
                    if (!validateForm()) {
                        e.preventDefault();
                        e.stopPropagation();
                        alert('Please fill in all required fields before continuing.');
                        return false;
                    }
                });

                // MOBILE FIX: Trigger form submission on touchend since click doesn't fire
                let isSubmitting = false;
                let touchMoved = false;

                submitBtn.addEventListener('touchstart', function(e) {
                    touchMoved = false;
                    this.style.opacity = '0.9';
                    this.style.transform = 'scale(0.98)';
                }, { passive: true });

                submitBtn.addEventListener('touchmove', function(e) {
                    touchMoved = true;
                }, { passive: true });

                submitBtn.addEventListener('touchend', function(e) {
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';

                    // Trigger form submission on touch (since click doesn't fire on mobile)
                    if (!touchMoved && !isSubmitting && validateForm()) {
                        isSubmitting = true;
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                    }
                }, { passive: true });

                submitBtn.addEventListener('mousedown', function(e) {
                    this.style.opacity = '0.9';
                    this.style.transform = 'scale(0.98)';
                });

                submitBtn.addEventListener('mouseup', function(e) {
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';
                });

                // Prevent double submission
                submitBtn.addEventListener('click', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return;
                    }
                    isSubmitting = true;
                    setTimeout(() => { isSubmitting = false; }, 3000);
                });
            }
        });
        
        // Payment method selection styling and form target handling
        const checkoutForm = document.getElementById('checkoutForm');
        const isInIframe = window.self !== window.top;
        // Get app domain from config (APP_URL in .env)
        const appUrl = '{{ config("app.url") }}';
        const appHost = appUrl ? new URL(appUrl).hostname : null;
        const currentHost = window.location.hostname;
        const isOnAppDomain = appHost && (currentHost === appHost || currentHost.endsWith('.' + appHost));
        
        document.querySelectorAll('.payment-method-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    card.querySelector('.payment-method-check').classList.add('hidden');
                    card.querySelector('div').classList.remove('border-indigo-500', 'bg-indigo-50',
                        'dark:bg-indigo-900/20');
                });
                if (this.checked) {
                    const card = this.closest('.payment-method-card');
                    card.querySelector('.payment-method-check').classList.remove('hidden');
                    card.querySelector('div').classList.add('border-indigo-500', 'bg-indigo-50',
                        'dark:bg-indigo-900/20');
                    
                    // If Coinbase Commerce is selected, we're in an iframe, and NOT on app's own domain
                    // Set form target to "_top" to break out of the iframe
                    if (this.value === 'coinbase_commerce' && isInIframe && !isOnAppDomain && checkoutForm) {
                        checkoutForm.target = '_top';
                    } else if (checkoutForm) {
                        checkoutForm.removeAttribute('target');
                    }
                }
            });
        });
        
        // Subscription Type Radio Button Functionality
        const renewalSection = document.getElementById('renewal-subscription-section');
        const renewalOrderNumberInput = document.getElementById('renewal_order_number');
        const referralCodeSection = document.getElementById('referral-code-section');
        
        // Referral code elements (defined early to avoid hoisting issues)
        const referralCodeInput = document.getElementById('referral_code');
        const validateReferralBtn = document.getElementById('validate-referral-btn');
        const referralValidationMessage = document.getElementById('referral-validation-message');

        document.querySelectorAll('.subscription-type-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.subscription-type-card').forEach(card => {
                    card.querySelector('.subscription-type-check').classList.add('hidden');
                    card.querySelector('div').classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                });
                if (this.checked) {
                    const card = this.closest('.subscription-type-card');
                    card.querySelector('.subscription-type-check').classList.remove('hidden');
                    card.querySelector('div').classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');

                    // Show/hide renewal subscription section
                    if (renewalSection) {
                        if (this.value === 'renewal') {
                            renewalSection.classList.remove('hidden');
                            // Fetch subscriptions when renewal is selected
                            fetchSubscriptions();
                        } else {
                            renewalSection.classList.add('hidden');
                            // Clear renewal order number when switching to new subscription
                            if (renewalOrderNumberInput) {
                                renewalOrderNumberInput.value = '';
                            }
                        }
                    }

                    // Show/hide referral code section (only for new subscriptions)
                    if (referralCodeSection) {
                        console.log('Subscription type changed to:', this.value);
                        console.log('Referral section element found:', referralCodeSection);
                        if (this.value === 'new') {
                            console.log('Showing referral section');
                            referralCodeSection.classList.remove('hidden');
                        } else {
                            console.log('Hiding referral section');
                            referralCodeSection.classList.add('hidden');
                            // Clear referral code when switching to renewal
                            if (referralCodeInput) {
                                referralCodeInput.value = '';
                                referralCodeInput.classList.remove('border-red-500', 'border-green-500');
                                if (referralValidationMessage) {
                                    referralValidationMessage.innerHTML = '';
                                }
                            }
                        }
                    } else {
                        console.log('Referral section not found');
                    }
                }
            });
        });

        // Referral Code Validation
        const emailInput = document.querySelector('input[name="email"]');

        if (validateReferralBtn && referralCodeInput) {
            validateReferralBtn.addEventListener('click', function() {
                const referralCode = referralCodeInput.value.trim().toUpperCase();
                const email = emailInput ? emailInput.value.trim() : '';

                if (!referralCode) {
                    referralValidationMessage.innerHTML = '<p class="text-yellow-600 dark:text-yellow-400">Please enter a referral code</p>';
                    return;
                }

                if (!email) {
                    referralValidationMessage.innerHTML = '<p class="text-yellow-600 dark:text-yellow-400">Please enter your email address first</p>';
                    return;
                }

                // Validate format (alphanumeric, 8-12 chars)
                if (!/^[A-Z0-9]{8,12}$/.test(referralCode)) {
                    referralValidationMessage.innerHTML = '<p class="text-red-600 dark:text-red-400">Invalid referral code format</p>';
                    referralCodeInput.classList.add('border-red-500');
                    return;
                }

                // Show loading state
                validateReferralBtn.disabled = true;
                validateReferralBtn.textContent = 'Validating...';
                referralValidationMessage.innerHTML = '<p class="text-gray-600 dark:text-gray-400">Validating referral code...</p>';

                // Validate via AJAX to check if code exists
                fetch('{{ route('checkout.validate-referral') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        referral_code: referralCode,
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    referralCodeInput.value = referralCode;
                    validateReferralBtn.disabled = false;
                    validateReferralBtn.textContent = 'Validate';
                    
                    if (data.valid) {
                        referralValidationMessage.innerHTML = '<p class="text-green-600 dark:text-green-400">✓ ' + data.message + '</p>';
                        referralCodeInput.classList.remove('border-red-500');
                        referralCodeInput.classList.add('border-green-500');
                    } else {
                        referralValidationMessage.innerHTML = '<p class="text-red-600 dark:text-red-400">✗ ' + data.message + '</p>';
                        referralCodeInput.classList.remove('border-green-500');
                        referralCodeInput.classList.add('border-red-500');
                    }
                })
                .catch(error => {
                    console.error('Validation error:', error);
                    referralValidationMessage.innerHTML = '<p class="text-red-600 dark:text-red-400">✗ Unable to validate referral code</p>';
                    referralCodeInput.classList.remove('border-green-500');
                    referralCodeInput.classList.add('border-red-500');
                    validateReferralBtn.disabled = false;
                    validateReferralBtn.textContent = 'Validate';
                });
            });

            // Auto-uppercase referral code input
            referralCodeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                referralCodeInput.classList.remove('border-red-500', 'border-green-500');
                referralValidationMessage.innerHTML = '';
            });

            // Pre-fill from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const refParam = urlParams.get('ref');
            if (refParam && !referralCodeInput.value) {
                referralCodeInput.value = refParam.toUpperCase();
            }
        }

        // Initialize both payment method and subscription type selections on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectedPaymentRadio = document.querySelector('.payment-method-radio:checked');
            if (selectedPaymentRadio) {
                selectedPaymentRadio.dispatchEvent(new Event('change'));
                
                // Also set form target if Coinbase Commerce is pre-selected, in iframe, and NOT on app's own domain
                if (selectedPaymentRadio.value === 'coinbase_commerce' && isInIframe && !isOnAppDomain && checkoutForm) {
                    checkoutForm.target = '_top';
                }
            }
            
            const selectedSubscriptionRadio = document.querySelector('.subscription-type-radio:checked');
            if (selectedSubscriptionRadio) {
                selectedSubscriptionRadio.dispatchEvent(new Event('change'));
            }

            // Initialize referral section visibility based on initial selection
            const initialSubscriptionType = document.querySelector('input[name="subscription_type"]:checked');
            console.log('Initial subscription type:', initialSubscriptionType ? initialSubscriptionType.value : 'none');
            console.log('Referral section element:', referralCodeSection);
            
            if (initialSubscriptionType && referralCodeSection) {
                if (initialSubscriptionType.value === 'renewal') {
                    console.log('Hiding referral section on page load for renewal');
                    referralCodeSection.classList.add('hidden');
                } else {
                    console.log('Showing referral section on page load for new subscription');
                    referralCodeSection.classList.remove('hidden');
                }
            } else {
                console.log('Could not initialize referral section visibility');
                // Default to visible for new subscriptions (default selection)
                if (referralCodeSection) {
                    referralCodeSection.classList.remove('hidden');
                }
            }
        });

        // Fetch subscriptions when email is entered and renewal is selected
        const renewalSubscriptionsContainer = document.getElementById('renewal-subscriptions-container');
        let fetchTimeout = null;

        function fetchSubscriptions() {
            const email = emailInput.value.trim();
            const renewalRadio = document.querySelector('input[name="subscription_type"][value="renewal"]');

            // Only fetch if renewal is selected and email is valid
            if (!renewalRadio || !renewalRadio.checked || !email || !email.includes('@')) {
                return;
            }

            // Show loading state
            renewalSubscriptionsContainer.innerHTML = `
                <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-center">
                    <svg class="animate-spin h-6 w-6 mx-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Loading subscriptions...</p>
                </div>
            `;

            // Fetch subscriptions via AJAX
            fetch('{{ route('checkout.fetch-subscriptions') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.subscriptions.length > 0) {
                    renderSubscriptions(data.subscriptions);
                } else {
                    renewalSubscriptionsContainer.innerHTML = `
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                No active subscriptions found for this email address.
                            </p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching subscriptions:', error);
                renewalSubscriptionsContainer.innerHTML = `
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                        <p class="text-sm text-red-800 dark:text-red-300">
                            Error loading subscriptions. Please try again.
                        </p>
                    </div>
                `;
            });
        }

        function renderSubscriptions(subscriptions) {
            let html = '<div class="space-y-3"><p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Select the subscription you want to renew:</p>';

            subscriptions.forEach(subscription => {
                const statusClass = subscription.is_active
                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                    : subscription.is_expired
                    ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';

                html += `
                    <label class="renewal-subscription-card relative cursor-pointer group block">
                        <input type="radio" name="renewal_order_number_display" value="${subscription.order_number}"
                            class="sr-only renewal-subscription-radio">
                        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            ${subscription.plan_name}
                                        </h4>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full ${statusClass}">
                                            ${subscription.status.charAt(0).toUpperCase() + subscription.status.slice(1)}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400">
                                        <div><span class="font-medium">Order:</span> ${subscription.order_number}</div>
                                        ${subscription.expires_at ? `<div><span class="font-medium">Expires:</span> ${subscription.expires_at}</div>` : ''}
                                    </div>
                                </div>
                                <div class="renewal-subscription-check hidden w-6 h-6 bg-indigo-600 rounded-full text-white flex items-center justify-center ml-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                `;
            });

            html += '</div>';
            renewalSubscriptionsContainer.innerHTML = html;

            // Re-attach event listeners to new radio buttons
            attachRenewalSubscriptionListeners();
        }

        function attachRenewalSubscriptionListeners() {
            document.querySelectorAll('.renewal-subscription-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.renewal-subscription-card').forEach(card => {
                        const div = card.querySelector('div');
                        div.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                        div.classList.add('border-gray-200', 'dark:border-gray-700');
                        card.querySelector('.renewal-subscription-check').classList.add('hidden');
                    });
                    if (this.checked) {
                        const card = this.closest('.renewal-subscription-card');
                        const div = card.querySelector('div');
                        div.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                        div.classList.remove('border-gray-200', 'dark:border-gray-700');
                        card.querySelector('.renewal-subscription-check').classList.remove('hidden');

                        // Update hidden input with selected order number
                        if (renewalOrderNumberInput) {
                            renewalOrderNumberInput.value = this.value;
                        }
                    }
                });
            });
        }

        // Debounce email input to fetch subscriptions
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearTimeout(fetchTimeout);
                fetchTimeout = setTimeout(() => {
                    const renewalRadio = document.querySelector('input[name="subscription_type"][value="renewal"]');
                    if (renewalRadio && renewalRadio.checked) {
                        fetchSubscriptions();
                    }
                }, 500); // Wait 500ms after user stops typing
            });
        }
    </script>

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

        /* Subscription Type Cards - Match Payment Method Style */
        .subscription-type-radio:checked + div {
            border-color: #6366f1;
        }

        .subscription-type-radio:checked + div .dark\\:bg-gray-800 {
            background-color: #312e81;
        }

        .subscription-type-card:hover .subscription-type-radio:not(:checked) + div {
            border-color: #6366f1;
        }

        .subscription-type-card:hover .subscription-type-radio:not(:checked) + div .dark\\:bg-gray-800 {
            background-color: #1f2937;
        }
    </style>
@endsection
