@extends('layouts.checkout')

@section('title', 'Checkout - ' . $product->name)

@section('content')
    <div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-10 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Secure Checkout
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Complete your purchase of {{ $product->name }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order Summary -->
                <aside class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm sticky top-10 overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                <span class="inline-flex w-8 h-8 rounded-md bg-gradient-to-br from-indigo-500 to-blue-500 items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </span>
                                <span>Order Summary</span>
                            </h2>

                            <div class="mt-6 space-y-4">
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $product->name }}
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                                    {{ $product->product_type === 'service' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                                    {{ $product->product_type === 'digital' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300' : '' }}
                                                    {{ $product->product_type === 'other' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                                    {{ ucfirst($product->product_type) }}
                                                </span>
                                            </p>
                                            @if($product->short_description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                    {{ $product->short_description }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 ml-4">
                                            ${{ number_format($product->price, 2) }}
                                        </div>
                                    </div>

                                    @if($product->description)
                                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($product->description, 150) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-gray-900 dark:text-gray-200">Total</span>
                                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">One-time payment</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-100 dark:bg-gray-700 py-3 px-6 text-sm text-center text-gray-600 dark:text-gray-300">
                            Secure & encrypted checkout
                        </div>
                    </div>
                </aside>

                <!-- Right Column: Form -->
                <main class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                        @if ($errors->any())
                            <div class="mb-6 p-4 border border-red-200 bg-red-50 rounded-xl text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('custom-product.checkout.submit', $product) }}" class="space-y-4 touch-manipulation">
                            @csrf
                            <input type="hidden" name="source" value="{{ $source ?? request('source', 'custom_product') }}">

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

                            <!-- Custom Fields -->
                            @if($product->custom_fields && count($product->custom_fields) > 0)
                            <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Additional Information</h2>
                                <style>
                                    .custom-fields-grid {
                                        display: grid;
                                        grid-template-columns: repeat(1, minmax(0, 1fr));
                                        gap: 1rem;
                                    }
                                    @media (min-width: 768px) {
                                        .custom-fields-grid {
                                            grid-template-columns: repeat(12, minmax(0, 1fr));
                                        }
                                    }
                                    .custom-field-half { grid-column: span 12; }
                                    .custom-field-third { grid-column: span 12; }
                                    .custom-field-quarter { grid-column: span 12; }
                                    .custom-field-full { grid-column: span 12; }
                                    @media (min-width: 768px) {
                                        .custom-field-half { grid-column: span 6; }
                                        .custom-field-third { grid-column: span 4; }
                                        .custom-field-quarter { grid-column: span 3; }
                                        .custom-field-full { grid-column: span 12; }
                                    }
                                </style>
                                <div class="custom-fields-grid">
                                    @foreach($product->custom_fields as $index => $field)
                                    @php
                                        $fieldType = $field['type'] ?? 'text';
                                        $fieldName = "custom_fields[{$index}]";
                                        $oldValue = old('custom_fields.' . $index);
                                        $isRequired = $field['required'] ?? false;
                                        $options = $field['options'] ?? [];
                                        $width = $field['width'] ?? 'full';
                                        $layout = $field['layout'] ?? 'vertical';
                                        
                                        // Width classes using custom CSS classes for reliable grid
                                        $widthClass = match($width) {
                                            'half' => 'custom-field-half',
                                            'third' => 'custom-field-third',
                                            'quarter' => 'custom-field-quarter',
                                            default => 'custom-field-full',
                                        };
                                        
                                        // Layout classes for radio/checkbox
                                        $layoutClass = ($layout === 'horizontal' && in_array($fieldType, ['radio', 'checkbox'])) 
                                            ? 'flex flex-wrap gap-4' 
                                            : 'space-y-2';
                                    @endphp
                                    <div class="{{ $widthClass }}">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ $field['label'] }} @if($isRequired)<span class="text-red-500">*</span>@endif
                                        </label>

                                        @if($fieldType === 'textarea')
                                            <textarea name="{{ $fieldName }}" 
                                                      @if($isRequired) required @endif
                                                      rows="4"
                                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $oldValue }}</textarea>
                                        
                                        @elseif($fieldType === 'select' && !empty($options))
                                            <select name="{{ $fieldName }}" 
                                                    @if($isRequired) required @endif
                                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Select an option</option>
                                                @foreach($options as $option)
                                                    <option value="{{ $option }}" {{ $oldValue === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        
                                        @elseif($fieldType === 'radio' && !empty($options))
                                            <div class="{{ $layoutClass }}">
                                                @foreach($options as $option)
                                                    <label class="flex items-center {{ $layout === 'horizontal' ? 'mr-4' : '' }}">
                                                        <input type="radio" 
                                                               name="{{ $fieldName }}" 
                                                               value="{{ $option }}"
                                                               {{ $oldValue === $option ? 'checked' : '' }}
                                                               @if($isRequired) required @endif
                                                               class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                        <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        
                                        @elseif($fieldType === 'checkbox')
                                            @if(!empty($options))
                                                {{-- Multiple checkboxes with options --}}
                                                <div class="{{ $layoutClass }}">
                                                    @foreach($options as $option)
                                                        @php
                                                            $checkboxName = "custom_fields[{$index}][]";
                                                            $checkboxValue = $oldValue;
                                                            $isChecked = is_array($oldValue) ? in_array($option, $oldValue) : ($oldValue === $option);
                                                        @endphp
                                                        <label class="flex items-center {{ $layout === 'horizontal' ? 'mr-4' : '' }}">
                                                            <input type="checkbox" 
                                                                   name="{{ $checkboxName }}" 
                                                                   value="{{ $option }}"
                                                                   {{ $isChecked ? 'checked' : '' }}
                                                                   @if($isRequired && $loop->first) required @endif
                                                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @else
                                                {{-- Single checkbox --}}
                                                <label class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="{{ $fieldName }}" 
                                                           value="1"
                                                           {{ $oldValue ? 'checked' : '' }}
                                                           @if($isRequired) required @endif
                                                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Yes</span>
                                                </label>
                                            @endif
                                        
                                        @else
                                            <input type="{{ $fieldType === 'email' ? 'email' : ($fieldType === 'number' ? 'number' : 'text') }}" 
                                                   name="{{ $fieldName }}" 
                                                   value="{{ $oldValue }}" 
                                                   @if($isRequired) required @endif
                                                   @if($fieldType === 'number') step="any" @endif
                                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </section>
                            @endif

                            <!-- Payment Method -->
                            <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @foreach ($availablePaymentMethods as $method)
                                        <label class="payment-method-card relative cursor-pointer group">
                                            <input type="radio" name="payment_method" value="{{ $method['key'] ?? '' }}"
                                                class="sr-only payment-method-radio"
                                                {{ old('payment_method', $defaultPaymentMethod) === ($method['key'] ?? '') ? 'checked' : '' }}>
                                            <div class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200 flex flex-col items-center justify-center text-center cursor-pointer pointer-events-none select-none">
                                                <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                                    @switch($method['key'])
                                                        @case('stripe')
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                        @break

                                                        @case('paypal')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-paypal" viewBox="0 0 16 16">
                                                                <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                                            </svg>
                                                        @break

                                                        @case('crypto')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-currency-bitcoin" viewBox="0 0 16 16">
                                                                <path d="M5.5 13v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.5v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.084c1.992 0 3.416-1.033 3.416-2.82 0-1.502-1.007-2.323-2.186-2.44v-.088c.97-.242 1.683-.974 1.683-2.19C11.997 3.93 10.847 3 9.092 3H9V1.75a.25.25 0 0 0-.25-.25h-1a.25.25 0 0 0-.25.25V3h-.573V1.75a.25.25 0 0 0-.25-.25H5.75a.25.25 0 0 0-.25.25V3l-1.998.011a.25.25 0 0 0-.25.25v.989c0 .137.11.25.248.25l.755-.005a.75.75 0 0 1 .745.75v5.505a.75.75 0 0 1-.75.75l-.748.011a.25.25 0 0 0-.25.25v1c0 .138.112.25.25.25zm1.427-8.513h1.719c.906 0 1.438.498 1.438 1.312 0 .871-.575 1.362-1.877 1.362h-1.28zm0 4.051h1.84c1.137 0 1.756.58 1.756 1.524 0 .953-.626 1.45-2.158 1.45H6.927z"/>
                                                            </svg>
                                                        @break

                                                        @default
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                    @endswitch
                                                </div>

                                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $method['name'] ?? ucfirst($method['key'] ?? 'method') }}
                                                </p>

                                                <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 flex items-center justify-center payment-method-indicator opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </section>

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold py-4 px-6 rounded-lg hover:from-indigo-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2 touch-manipulation"
                                        style="min-height: 48px;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <span>Proceed to Payment</span>
                                </button>
                                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                                    Your payment information is secure and encrypted
                                </p>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle payment method selection visual feedback and mobile button fixes
        document.addEventListener('DOMContentLoaded', function() {
            // Fix submit button for mobile
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('touchstart', function(e) {
                    this.style.opacity = '0.9';
                }, { passive: true });
                
                submitBtn.addEventListener('touchend', function(e) {
                    this.style.opacity = '1';
                    
                    // Prevent default and submit form programmatically
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const form = this.closest('form');
                    if (form) {
                        form.submit();
                    }
                    
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
            
            document.querySelectorAll('.payment-method-radio').forEach((radio) => {
                radio.addEventListener('change', function() {
                    // Remove active styling from all cards
                    document.querySelectorAll('.payment-method-card').forEach(card => {
                        const div = card.querySelector('div');
                        div.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                        div.classList.add('border-gray-200', 'dark:border-gray-700');
                        
                        // Hide indicators on all cards
                        const indicator = card.querySelector('.payment-method-indicator');
                        if (indicator) {
                            indicator.classList.add('opacity-0');
                        }
                    });
                    
                    // Add active styling to selected card
                    if (this.checked) {
                        const card = this.closest('.payment-method-card');
                        const div = card.querySelector('div');
                        div.classList.remove('border-gray-200', 'dark:border-gray-700');
                        div.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                        
                        // Show indicator on selected card
                        const indicator = card.querySelector('.payment-method-indicator');
                        if (indicator) {
                            indicator.classList.remove('opacity-0');
                        }
                    }
                });
                
                // Initialize visual state for pre-selected radio buttons
                if (radio.checked) {
                    radio.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
    @endpush
@endsection

