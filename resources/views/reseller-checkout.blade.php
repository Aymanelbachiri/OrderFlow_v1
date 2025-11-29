@extends('layouts.checkout')

@section('title', 'Reseller Checkout')

@section('content')
    <div class="min-h-screen flex items-center py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8 animate-fade-in-up">
                <h1 class="text-4xl font-bold  dark:text-white mb-2">Reseller Checkout</h1>
                <p class="text-lg dark:text-white/60 text-[#201E1F]/60">Complete your credit pack purchase</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Credit Pack Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class=" rounded-2xl border border-[#e5e7eb] shadow-md sticky top-8 animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        <div class="p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold dark:text-white text-[#201E1F]">Reseller Pack</h3>
                            </div>

                            @if ($creditPack)
                                <div class="space-y-4">
                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-[#e5e7eb]">
                                        <div class="text-center mb-4">
                                            <div
                                                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full mb-3">
                                                <span
                                                    class="text-2xl font-bold text-blue-600">{{ number_format($creditPack->credits_amount) }}</span>
                                            </div>
                                            <h4 class="font-semibold text-[#201E1F] dark:text-white text-lg mb-1">{{ $creditPack->name }}
                                            </h4>
                                            @if ($creditPack->description)
                                                <p class="text-sm text-[#201E1F]/60 dark:text-white/60">{{ $creditPack->description }}</p>
                                            @endif
                                        </div>

                                        <div class="border-t border-gray-100 pt-4">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-[#201E1F]/60 dark:text-white">Credits</span>
                                                <span
                                                    class="font-semibold text-[#201E1F] dark:text-white">{{ number_format($creditPack->credits_amount) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-semibold text-blue-900">Total</span>
                                            <span
                                                class="text-3xl font-bold text-blue-600">${{ number_format($creditPack->price, 2) }}</span>
                                        </div>
                                        <p class="text-sm text-blue-700 mt-2">One-time payment</p>
                                    </div>

                                    <!-- Benefits -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                        <h5 class="font-medium text-blue-900 mb-3">Reseller Benefits</h5>
                                        <ul class="space-y-2 text-sm text-blue-800">
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Access to reseller panel
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Manage your own clients
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Flexible pricing options
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                                    <svg class="w-12 h-12 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                    <h4 class="font-medium text-yellow-800 mb-1">No Pack Selected</h4>
                                    <p class="text-sm text-yellow-700">Please select a credit pack.</p>
                                </div>
                            @endif

                            <!-- Security Badge -->
                            <div class="mt-6 pt-6 border-t border-[#e5e7eb]">
                                <div class="flex items-center justify-center space-x-2 text-sm text-[#201E1F]/60 dark:text-white/60">
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
                    </div>
                </div>
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class=" rounded-2xl border border-[#e5e7eb]/10 shadow-md animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        <div class="p-4">
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
                                <div>
                                    <div class="flex items-center space-x-3 mb-6">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h2 class="text-xl font-semibold text-[#201E1F] dark:text-white">Reseller Information</h2>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-[#201E1F]/60 dark:text-white mb-2">Full Name
                                                *</label>
                                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                                required
                                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-[#e5e7eb] rounded-lg dark:text-white text-[#201E1F]  placeholder-[#201E1F]/40 dark:placeholder-white/40 focus:border-[#e5e7eb] focus:ring-2 focus:ring-[#e5e7eb]/20 transition-all duration-300 @error('full_name') border-red-300 @enderror"
                                                placeholder="John Doe">
                                            @error('full_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-[#201E1F]/60 dark:text-white mb-2">Email
                                                    Address *</label>
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                    required
                                                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-[#e5e7eb] rounded-lg text-[#201E1F] dark:text-white dark:placeholder-white/40 placeholder-[#201E1F]/40 focus:border-[#e5e7eb] focus:ring-2 focus:ring-[#e5e7eb]/20 transition-all duration-300 @error('email') border-red-300 @enderror"
                                                    placeholder="john@example.com">
                                                @error('email')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-[#201E1F]/60 dark:text-white mb-2">Phone
                                                    Number *</label>
                                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                                    required
                                                    class="w-full px-4 py-3 bg-white border dark:bg-gray-800 border-[#e5e7eb] rounded-lg text-[#201E1F] dark:text-white dark:placeholder-white/40 placeholder-[#201E1F]/40 focus:border-[#e5e7eb] focus:ring-2 focus:ring-[#e5e7eb]/20 transition-all duration-300 @error('phone') border-red-300 @enderror"
                                                    placeholder="+1 555 000 0000">
                                                @error('phone')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-[#201E1F]/60 dark:text-white mb-2">Panel Username
                                                *</label>
                                            <input type="text" name="panel_username"
                                                value="{{ old('panel_username') }}" required
                                                class="w-full px-4 py-3 bg-white border dark:bg-gray-800 border-[#e5e7eb] rounded-lg text-[#201E1F dark:text-white dark:placeholder-white/40 placeholder-[#201E1F]/40 focus:border-[#e5e7eb] focus:ring-2 focus:ring-[#e5e7eb]/20 transition-all duration-300 @error('panel_username') border-red-300 @enderror"
                                                placeholder="your_panel_username">
                                            <p class="mt-2 text-sm text-[#201E1F]/60 dark:text-white/70">This will be your username to access
                                                the reseller panel</p>
                                            @error('panel_username')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="border-t border-[#e5e7eb] pt-8">
                                    <div class="flex items-center space-x-3 mb-6">

                                        <h2 class="text-xl font-semibold text-[#201E1F] dark:text-white">Choose Payment Method</h2>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @foreach ($availablePaymentMethods as $method)
                                            <label class="payment-method-card relative cursor-pointer group">
                                                <input type="radio" name="payment_method" value="{{ $method['key'] }}"
                                                    class="sr-only payment-method-radio"
                                                    {{ $defaultPaymentMethod === $method['key'] ? 'checked' : '' }}
                                                    required>

                                                <div
                                                    class="bg-white dark:bg-gray-800 border-2 rounded-xl p-4 text-center transition-all duration-300 hover:shadow-md group-hover:border-blue-500/30">

                                                    {{-- Icon Section --}}
                                                    <div
                                                        class="payment-method-icon dark:bg-gray-800  w-12 h-12 mx-auto mb-3 rounded-lg flex items-center justify-center">
                                                        @if (str_contains(strtolower($method['key']), 'credit') || str_contains(strtolower($method['key']), 'card'))
                                                            {{-- 💳 Credit Card Icon --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-credit-card-2-back" viewBox="0 0 16 16">
                                                                <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                                                                <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                                                              </svg>
                                                        @elseif(str_contains(strtolower($method['key']), 'paypal'))
                                                            {{-- 💰 PayPal Icon --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-paypal" viewBox="0 0 16 16">
                                                                <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                                              </svg>
                                                        @elseif(str_contains(strtolower($method['key']), 'crypto') ||
                                                                str_contains(strtolower($method['key']), 'usdt') ||
                                                                str_contains(strtolower($method['key']), 'trc20'))
                                                            
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-currency-bitcoin" viewBox="0 0 16 16">
                                                                <path d="M5.5 13v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.5v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.084c1.992 0 3.416-1.033 3.416-2.82 0-1.502-1.007-2.323-2.186-2.44v-.088c.97-.242 1.683-.974 1.683-2.19C11.997 3.93 10.847 3 9.092 3H9V1.75a.25.25 0 0 0-.25-.25h-1a.25.25 0 0 0-.25.25V3h-.573V1.75a.25.25 0 0 0-.25-.25H5.75a.25.25 0 0 0-.25.25V3l-1.998.011a.25.25 0 0 0-.25.25v.989c0 .137.11.25.248.25l.755-.005a.75.75 0 0 1 .745.75v5.505a.75.75 0 0 1-.75.75l-.748.011a.25.25 0 0 0-.25.25v1c0 .138.112.25.25.25zm1.427-8.513h1.719c.906 0 1.438.498 1.438 1.312 0 .871-.575 1.362-1.877 1.362h-1.28zm0 4.051h1.84c1.137 0 1.756.58 1.756 1.524 0 .953-.626 1.45-2.158 1.45H6.927z"/>
                                                              </svg>
                                                        @else
                                                            {{-- Default Icon --}}
                                                            <svg class="w-6 h-6 text-gray-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    {{-- Name --}}
                                                    <div class="payment-method-name font-medium text-[#201E1F] dark:text-white capitalize">
                                                        {{ str_replace('_', ' ', $method['key']) }}
                                                    </div>

                                                    {{-- Checkmark --}}
                                                    <div
                                                        class="payment-method-check hidden absolute top-3 right-3 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
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
                                </div>

                                <!-- Submit Button -->
                                <div class="border-t border-[#e5e7eb] pt-8">
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-500 to-blue-500/80 hover:from-blue-500/90 hover:to-blue-500 text-white px-8 py-4 rounded-xl text-lg font-semibold flex items-center justify-center space-x-3 transition-all duration-300 shadow-lg hover:shadow-xl touch-manipulation"
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
                    card.querySelector('.payment-method-check').classList.add('hidden');
                    card.querySelector('div').classList.remove('border-blue-500', 'bg-blue-500/5');
                    card.querySelector('div').classList.add('border-[#e5e7eb]');
                    card.querySelector('.payment-method-icon').classList.remove('bg-blue-500/10');
                    card.querySelector('.payment-method-icon').classList.add('bg-gray-100');
                });

                // Add active state to selected card
                if (this.checked) {
                    const card = this.closest('.payment-method-card');
                    card.querySelector('.payment-method-check').classList.remove('hidden');
                    card.querySelector('div').classList.add('border-blue-500', 'bg-blue-500/5');
                    card.querySelector('div').classList.remove('border-[#e5e7eb]');
                    card.querySelector('.payment-method-icon').classList.add('bg-blue-500/10');
                    card.querySelector('.payment-method-icon').classList.remove('bg-gray-100');
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

        .payment-method-icon {
            @apply bg-gray-100 text-gray-600;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover .payment-method-icon {
            @apply bg-blue-500/10 text-blue-500;
        }
    </style>
@endsection
