@extends('layouts.checkout')

@section('title', 'Reseller Checkout')

@section('content')
    <div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-10 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Reseller Checkout
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Complete your credit pack purchase
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Credit Pack Summary Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm sticky top-10 overflow-hidden animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                <span class="inline-flex w-8 h-8 rounded-md bg-gradient-to-br from-indigo-500 to-blue-500 items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </span>
                                <span>Reseller Pack</span>
                            </h2>

                            @if ($creditPack)
                                <div class="mt-6 space-y-4">
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $creditPack->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ number_format($creditPack->credits_amount) }} Credits
                                                </p>
                                            </div>
                                        </div>

                                        @if ($creditPack->features && is_array($creditPack->features))
                                            <ul class="mt-3 border-t border-gray-100 dark:border-gray-700 pt-3 space-y-2">
                                                @foreach (array_slice($creditPack->features, 0, 3) as $feature)
                                                    <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                                @if (count($creditPack->features) > 3)
                                                    <li class="text-sm text-gray-500 dark:text-gray-400">+ {{ count($creditPack->features) - 3 }} more</li>
                                                @endif
                                            </ul>
                                        @else
                                            <ul class="mt-3 border-t border-gray-100 dark:border-gray-700 pt-3 space-y-2">
                                                <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Access to reseller panel
                                                </li>
                                                <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Manage your own clients
                                                </li>
                                                <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Flexible pricing options
                                                </li>
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700 rounded-xl">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-900 dark:text-gray-200">Total</span>
                                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($creditPack->price, 2) }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">One-time payment</p>
                                    </div>
                                </div>
                            @else
                                <div class="mt-6 text-center bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4 text-yellow-700 dark:text-yellow-300">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p>No pack selected. Please select a credit pack.</p>
                                </div>
                            @endif
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                <span>Secure SSL encrypted payment</span>
                            </div>
                        </div>
                    </div>
                </aside>
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        <div class="p-8">
                            @if (session('error'))
                                <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Please correct the following
                                                errors:</h3>
                                            <ul class="mt-2 text-sm text-red-700 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>• {{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('reseller.checkout.submit') }}" class="space-y-4 touch-manipulation">
                                @csrf
                                <input type="hidden" name="reseller_credit_pack_id" value="{{ $planId }}" />
                                <input type="hidden" name="source" value="{{ request('source', 'reseller') }}" />

                                <!-- Reseller Information -->
                                <section>
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Reseller Information</h2>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                                required
                                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 @error('full_name') border-red-300 @enderror"
                                                placeholder="John Doe">
                                            @error('full_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                    required
                                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 @error('email') border-red-300 @enderror"
                                                    placeholder="john@example.com">
                                                @error('email')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number *</label>
                                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                                    required
                                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 @error('phone') border-red-300 @enderror"
                                                    placeholder="+1 555 000 0000">
                                                @error('phone')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Panel Username *</label>
                                            <input type="text" name="panel_username"
                                                value="{{ old('panel_username') }}" required
                                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 @error('panel_username') border-red-300 @enderror"
                                                placeholder="your_panel_username">
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">This will be your username to access the reseller panel</p>
                                            @error('panel_username')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </section>

                                <!-- Payment Method -->
                                <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h2>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        @foreach ($availablePaymentMethods as $method)
                                            <label class="payment-method-card relative cursor-pointer group">
                                                <input type="radio" name="payment_method" value="{{ $method['key'] }}"
                                                    class="sr-only payment-method-radio"
                                                    {{ $defaultPaymentMethod === $method['key'] ? 'checked' : '' }}
                                                    required>

                                                <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200 flex flex-col items-center justify-center text-center">

                                                    {{-- Icon Section --}}
                                                    <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                                        @if (str_contains(strtolower($method['key']), 'stripe') || str_contains(strtolower($method['key']), 'card'))
                                                            {{-- 💳 Credit Card Icon --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                                                                <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                                                            </svg>
                                                        @elseif(str_contains(strtolower($method['key']), 'paypal'))
                                                            {{-- 💰 PayPal Icon --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                                            </svg>
                                                        @elseif(str_contains(strtolower($method['key']), 'crypto') ||
                                                                str_contains(strtolower($method['key']), 'coinbase') ||
                                                                str_contains(strtolower($method['key']), 'usdt') ||
                                                                str_contains(strtolower($method['key']), 'trc20'))
                                                            {{-- 💰 Bitcoin/Crypto Icon --}}
                                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10 6V4M14 6V4M14 6H7M14 6C15.6569 6 17 7.34315 17 9C17 10.6569 15.6569 12 14 12M9 18L9 12M9 6V12M10 20V18M14 20V18M9 12H15C16.6569 12 18 13.3431 18 15C18 16.6569 16.6569 18 15 18H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        @else
                                                            {{-- Default Icon --}}
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    {{-- Name --}}
                                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $method['name'] ?? ucfirst($method['key'] ?? 'method') }}
                                                    </p>

                                                    {{-- Checkmark --}}
                                                    <div class="payment-method-check hidden absolute top-3 right-3 w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach

                                    </div>
                                    @error('payment_method')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </section>

                                <!-- Submit Button -->
                                <div class="mt-8">
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-8 py-4 rounded-xl text-lg font-semibold flex items-center justify-center space-x-2 transition-all duration-200 shadow-lg hover:shadow-xl"
                                        style="min-height: 48px;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span>Continue to Payment</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        // Mobile button fixes
        document.addEventListener('DOMContentLoaded', function() {
            // Fix submit button for mobile
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('touchstart', function(e) {
                    this.style.opacity = '0.9';
                }, { passive: true });
                
                // Add form validation before submit
                const form = submitBtn.closest('form');
                if (form) {
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
                        
                        return isValid;
                    }
                    
                    form.addEventListener('submit', function(e) {
                        if (!validateForm()) {
                    e.preventDefault();
                    e.stopPropagation();
                            alert('Please fill in all required fields before continuing.');
                            return false;
                        }
                    });
                    
                    submitBtn.addEventListener('touchend', function(e) {
                        this.style.opacity = '1';
                        
                        // Validate before submitting
                        if (!validateForm()) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                        
                        // If valid, trigger form submit
                        e.preventDefault();
                        e.stopPropagation();
                        form.requestSubmit();
                    
                    return false;
                }, { passive: false });
                }
                
                // Fix payment method cards for mobile
                const paymentCards = document.querySelectorAll('.payment-method-card');
                paymentCards.forEach(card => {
                    card.addEventListener('touchstart', function(e) {
                        this.style.transform = 'scale(0.98)';
                    }, { passive: true });
                    
                    card.addEventListener('touchend', function(e) {
                        this.style.transform = 'scale(1)';
                        // Trigger radio selection
                        const radio = this.querySelector('.payment-method-radio');
                        if (radio) {
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change'));
                        }
                        return false;
                    }, { passive: false });
                });
            }
        });
        
        // Handle payment method selection
        document.querySelectorAll('.payment-method-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove active state from all cards
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    const div = card.querySelector('div');
                    div.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                    div.classList.add('border-gray-200', 'dark:border-gray-700');
                    card.querySelector('.payment-method-check').classList.add('hidden');
                });

                // Add active state to selected card
                if (this.checked) {
                    const card = this.closest('.payment-method-card');
                    const div = card.querySelector('div');
                    div.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                    div.classList.remove('border-gray-200', 'dark:border-gray-700');
                    card.querySelector('.payment-method-check').classList.remove('hidden');
                }
            });
        });

        // Initialize selected payment method on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRadio = document.querySelector('.payment-method-radio:checked');
            if (selectedRadio) {
                selectedRadio.dispatchEvent(new Event('change'));
            }
        });
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
    </style>
@endsection
