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
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-700 dark:text-blue-300 text-sm mb-2">Your Payment is Protected</h4>
                            <div class="space-y-2 text-xs text-blue-600 dark:text-blue-400">
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
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-red-500 dark:text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-red-700 dark:text-red-300 mb-2">Payment Unavailable</h3>
                                <p class="text-red-600 dark:text-red-400">Stripe is not configured. Please contact our support team for assistance.</p>
                            </div>
                        @else
                            <form id="payment-form" class="space-y-6">
                                <!-- Payment Element -->
                                <div id="payment-element">
                                    <!-- Payment Element will be mounted here -->
                                </div>
                                <div id="payment-element-errors" role="alert" class="mt-3 text-sm text-red-600 dark:text-red-400"></div>
                                <!-- Security Notice -->
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 dark:text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-green-700 dark:text-green-300 text-sm mb-1">Secure Payment</h4>
                                            <p class="text-green-600 dark:text-green-400 text-sm">Your card information is encrypted and secure. We don't store your payment details.</p>
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

/* Stripe Payment Element Styling */
#payment-element {
    padding: 12px;
}

/* Dark mode support for Payment Element container */
.dark #payment-element {
    background-color: transparent;
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
// Initialize Stripe
const stripe = Stripe('{{ $stripePublicKey }}');

// Function to detect if dark mode is active
function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

// Function to get theme-appropriate appearance configuration
function getStripeAppearance() {
    const darkMode = isDarkMode();
    
    return {
        theme: darkMode ? 'night' : 'stripe',
        variables: {
            colorPrimary: '#0570de',
            colorBackground: darkMode ? '#1f2937' : '#ffffff', // gray-800 : white
            colorText: darkMode ? '#f9fafb' : '#201E1F', // gray-50 : dark
            colorTextSecondary: darkMode ? '#d1d5db' : '#6b7280', // gray-300 : gray-500
            colorTextPlaceholder: darkMode ? '#9ca3af' : '#9ca3af', // gray-400
            colorDanger: '#ef4444',
            colorBorder: darkMode ? '#374151' : '#e5e7eb', // gray-700 : gray-200
            colorIcon: darkMode ? '#9ca3af' : '#6b7280', // gray-400 : gray-500
            borderRadius: '8px',
            spacingUnit: '4px',
            fontFamily: 'system-ui, sans-serif',
            fontSizeBase: '16px'
        },
        rules: {
            '.Input': {
                border: darkMode ? '1px solid #374151' : '1px solid #e5e7eb',
                backgroundColor: darkMode ? '#1f2937' : '#ffffff',
                color: darkMode ? '#f9fafb' : '#201E1F',
                boxShadow: 'none',
            },
            '.Input:focus': {
                border: '1px solid #0570de',
                boxShadow: '0 0 0 3px rgba(214, 54, 19, 0.1)',
            },
            '.Input--invalid': {
                border: '1px solid #ef4444',
                color: darkMode ? '#f9fafb' : '#201E1F',
            },
            '.Label': {
                color: darkMode ? '#f9fafb' : '#201E1F',
            },
            '.Tab': {
                border: darkMode ? '1px solid #374151' : '1px solid #e5e7eb',
                backgroundColor: darkMode ? '#1f2937' : '#ffffff',
                color: darkMode ? '#d1d5db' : '#6b7280',
            },
            '.Tab--selected': {
                border: '1px solid #0570de',
                backgroundColor: darkMode ? '#374151' : '#f9fafb',
                color: darkMode ? '#f9fafb' : '#201E1F',
            },
            '.TabIcon': {
                color: darkMode ? '#9ca3af' : '#6b7280',
            },
            '.TabIcon--selected': {
                color: '#0570de',
            }
        }
    };
}

// Set up Stripe Elements with client secret and theme-aware appearance
const options = {
    clientSecret: '{{ $stripePaymentIntent->client_secret ?? '' }}',
    appearance: getStripeAppearance()
};

const elements = stripe.elements(options);

// Create and mount the Payment Element with billing details collection
const paymentElementOptions = {
    layout: 'accordion',
    // Collect billing details for better risk assessment (AVS - Address Verification)
    fields: {
        billingDetails: {
            address: 'auto', // Automatically collect billing address
        },
    },
};
const paymentElement = elements.create('payment', paymentElementOptions);
paymentElement.mount('#payment-element');

// Handle real-time validation errors from the Payment Element
paymentElement.on('change', function(event) {
    const displayError = document.getElementById('payment-element-errors');
    if (event.error) {
        const isDark = isDarkMode();
        displayError.innerHTML = `
            <div class="flex items-center space-x-2 ${isDark ? 'text-red-400' : 'text-red-600'}">
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

// Update Payment Element appearance when theme changes
function updatePaymentElementTheme() {
    const newAppearance = getStripeAppearance();
    elements.update({ appearance: newAppearance });
}

// Listen for theme changes
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            updatePaymentElementTheme();
        }
    });
});

// Observe the document element for class changes (dark mode toggle)
observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
});

// Handle form submission
document.getElementById('payment-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    // Set loading state
    submitButton.disabled = true;
    buttonText.classList.add('hidden');
    spinner.classList.remove('hidden');
    
    // Clear any previous error messages
    document.getElementById('payment-status').innerHTML = '';
    document.getElementById('payment-element-errors').textContent = '';
    
    try {
        const {error} = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: '{{ route("public.payment-intents.success", $paymentIntent) }}',
            },
        });
        
        if (error) {
            // This point will only be reached if there is an immediate error when
            // confirming the payment. Show error to your customer (for example, payment
            // details incomplete)
            const isDark = isDarkMode();
            document.getElementById('payment-status').innerHTML = `
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 ${isDark ? 'text-red-400' : 'text-red-500'} mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold ${isDark ? 'text-red-300' : 'text-red-700'} text-sm mb-1">Payment Failed</h4>
                            <p class="${isDark ? 'text-red-400' : 'text-red-600'} text-sm">${error.message}</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Reset button state
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        } else {
            // Your customer will be redirected to your `return_url`. For some payment
            // methods like iDEAL, your customer will be redirected to an intermediate
            // site first to authorize the payment, then redirected to the `return_url`.
            // The payment status will be checked on the return_url page.
        }
    } catch (error) {
        const isDark = isDarkMode();
        document.getElementById('payment-status').innerHTML = `
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 ${isDark ? 'text-red-400' : 'text-red-500'} mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold ${isDark ? 'text-red-300' : 'text-red-700'} text-sm mb-1">Connection Error</h4>
                        <p class="${isDark ? 'text-red-400' : 'text-red-600'} text-sm">Unable to process payment. Please check your connection and try again.</p>
                    </div>
                </div>
            </div>
        `;
        
        // Reset button state
        submitButton.disabled = false;
        buttonText.classList.remove('hidden');
        spinner.classList.add('hidden');
    }
});
</script>
@endif
@endsection