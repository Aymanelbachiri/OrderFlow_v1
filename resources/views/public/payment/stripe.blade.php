@extends('layouts.checkout')

@section('title', 'Credit Card Payment')

@section('content')
<div class="min-h-screen  flex items-center py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in-up">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Secure Payment</h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Complete your purchase with confidence</p>
            <div class="flex items-center justify-center space-x-2 mt-4">
                <div class="flex items-center space-x-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>SSL Secured</span>
                </div>
                <div class="text-gray-400">•</div>
                <span class="text-gray-600 dark:text-gray-300 text-sm">Payment ID: #{{ $paymentIntent->id }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-gray-700 shadow-lg sticky top-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="px-6 py-5 border-b border-blue-200 dark:border-gray-700">
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
                        <h3 class="text-2xl text-center py-4 font-bold text-gray-900 dark:text-white mb-2">
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
                                    <span class="text-gray-900 dark:text-white/70">Processing Fee:</span>
                                    <span class="text-green-600 font-medium">$0.00</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between font-semibold">
                                    <span class="text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-xl text-blue-500">${{ number_format($paymentIntent->amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Features -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-700 text-sm mb-2">Your Payment is Protected</h4>
                            <div class="space-y-2 text-xs text-blue-600">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>256-bit SSL Encryption</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>PCI DSS Compliant</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Fraud Protection</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="lg:col-span-3 order-1 lg:order-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-gray-700 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="px-6 py-5 border-b border-blue-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Payment Information</h2>
                        </div>
                    </div>

                    <div class="p-6">
                        <div id="payment-status" class="mb-6"></div>

                        @php $stripePublicKey = \App\Models\SystemSetting::get('stripe_public_key', ''); @endphp
                        @if(empty($stripePublicKey))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-red-700 mb-2">Payment Unavailable</h3>
                                <p class="text-red-600">Stripe is not configured. Please contact our support team for assistance.</p>
                            </div>
                        @else
                            <form id="payment-form" class="space-y-6">
                                <!-- Cardholder Name -->
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200">
                                    <label for="cardholder-name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Cardholder Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-900 dark:text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" id="cardholder-name" required 
                                               class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="John Doe">
                                    </div>
                                </div>

                                <!-- Card Information -->
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200">
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Card Information</label>
                                    <div class="relative">
                                        <div id="card-element" class="p-4 border border-gray-200 rounded-lg bg-white focus-within:border-[#D63613] focus-within:ring-2 focus-within:ring-[#D63613]/20 transition-all duration-300"></div>
                                        <div id="card-errors" role="alert" class="mt-3 text-sm text-red-600"></div>
                                    </div>
                                </div>

                                <!-- Security Notice -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-green-700 text-sm mb-1">Secure Payment</h4>
                                            <p class="text-green-600 text-sm">Your card information is encrypted and secure. We don't store your payment details.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" id="submit-button" 
                                        class="w-full bg-gradient-to-r from-blue-500 to-blue-700/80 hover:from-blue-700/90 hover:to-blue-500 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed text-white py-4 px-6 rounded-lg font-semibold text-lg flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                                    <span id="button-text" class="flex items-center space-x-2">
                                        
                                        <span>Complete Payment - ${{ number_format($paymentIntent->amount, 2) }}</span>
                                    </span>
                                    <span id="spinner" class="hidden flex items-center space-x-2">
                                        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                        <span>Processing...</span>
                                    </span>
                                </button>
                            </form>
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

/* Stripe Elements Styling */
.StripeElement {
    background: white;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.StripeElement--focus {
    border-color: #D63613;
    box-shadow: 0 0 0 3px rgba(214, 54, 19, 0.1);
}

.StripeElement--invalid {
    border-color: #ef4444;
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

@if(!empty($stripePublicKey))
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ $stripePublicKey }}');
const elements = stripe.elements({
    appearance: {
        theme: 'stripe',
        variables: {
            colorPrimary: '#D63613',
            colorBackground: '#ffffff',
            colorText: '#201E1F',
            colorDanger: '#ef4444',
            fontFamily: 'system-ui, sans-serif',
            spacingUnit: '4px',
            borderRadius: '8px'
        }
    }
});

const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            fontFamily: 'system-ui, sans-serif',
            color: '#201E1F',
            '::placeholder': {
                color: '#9ca3af',
            },
        },
    },
});

cardElement.mount('#card-element');

cardElement.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.innerHTML = `
            <div class="flex items-center space-x-2 text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>${event.error.message}</span>
            </div>
        `;
    } else {
        displayError.textContent = '';
    }
});

document.getElementById('payment-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const cardholderName = document.getElementById('cardholder-name').value;
    
    // Validation
    if (!cardholderName.trim()) {
        document.getElementById('payment-status').innerHTML = `
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-center space-x-2 text-amber-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Please enter the cardholder name.</span>
                </div>
            </div>
        `;
        return;
    }
    
    // Set loading state
    submitButton.disabled = true;
    buttonText.classList.add('hidden');
    spinner.classList.remove('hidden');
    
    try {
        const {error, paymentIntent} = await stripe.confirmCardPayment('{{ $stripePaymentIntent->client_secret ?? '' }}', {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardholderName
                }
            }
        });
        
        if (error) {
            document.getElementById('payment-status').innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-red-700 text-sm mb-1">Payment Failed</h4>
                            <p class="text-red-600 text-sm">${error.message}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Show success message
            document.getElementById('payment-status').innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-green-700 text-sm mb-1">Payment Successful!</h4>
                            <p class="text-green-600 text-sm">Redirecting you to confirmation page...</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Submit success form
            setTimeout(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("public.payment-intents.success", $paymentIntent) }}';
                form.innerHTML = `
                    <input type="hidden" name="_token" value='{{ csrf_token() }}'/>
                    <input type="hidden" name="payment_id" value='${paymentIntent.id}'/>
                    <input type="hidden" name="payment_method" value='stripe'/>
                    <input type="hidden" name="payment_details" value='${JSON.stringify(paymentIntent)}'/>
                `;
                document.body.appendChild(form);
                form.submit();
            }, 1500);
        }
    } catch (error) {
        document.getElementById('payment-status').innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-700 text-sm mb-1">Connection Error</h4>
                        <p class="text-red-600 text-sm">Unable to process payment. Please check your connection and try again.</p>
                    </div>
                </div>
            </div>
        `;
    } finally {
        // Reset button state
        submitButton.disabled = false;
        buttonText.classList.remove('hidden');
        spinner.classList.add('hidden');
    }
});
</script>
@endif
@endsection