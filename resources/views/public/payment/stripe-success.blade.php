@extends('layouts.checkout')

@section('title', 'Processing Payment')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <div id="payment-status" class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-center space-x-3">
                    <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <div>
                        <h3 class="font-semibold text-blue-700 text-lg">Processing Payment</h3>
                        <p class="text-blue-600 text-sm mt-1">Please wait while we verify your payment...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($stripePublicKey) && !empty($clientSecret))
<script src="https://js.stripe.com/v3/"></script>
<script>
// Initialize Stripe.js using your publishable key
const stripe = Stripe('{{ $stripePublicKey }}');

// Retrieve the "payment_intent_client_secret" query parameter appended to
// your return_url by Stripe.js
const clientSecret = '{{ $clientSecret }}';

// Retrieve the PaymentIntent
stripe.retrievePaymentIntent(clientSecret).then(({paymentIntent, error}) => {
    const statusContainer = document.getElementById('payment-status');
    
    if (error) {
        statusContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-8 h-8 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-left">
                        <h3 class="font-semibold text-red-700 text-lg mb-1">Payment Verification Failed</h3>
                        <p class="text-red-600 text-sm">${error.message}</p>
                        <a href="{{ route('public.payment.stripe', $paymentIntent) }}" class="mt-4 inline-block text-sm text-red-700 hover:underline">Try again</a>
                    </div>
                </div>
            </div>
        `;
        return;
    }
    
    // Inspect the PaymentIntent `status` to indicate the status of the payment
    // to your customer.
    //
    // Some payment methods will immediately succeed or fail upon
    // confirmation, while others will first enter a `processing` state.
    switch (paymentIntent.status) {
        case 'succeeded':
            statusContainer.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-8 h-8 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-left">
                            <h3 class="font-semibold text-green-700 text-lg mb-1">Payment Successful!</h3>
                            <p class="text-green-600 text-sm">Your payment has been confirmed. Redirecting...</p>
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
            break;

        case 'processing':
            statusContainer.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-8 h-8 text-yellow-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-left">
                            <h3 class="font-semibold text-yellow-700 text-lg mb-1">Payment Processing</h3>
                            <p class="text-yellow-600 text-sm">We'll update you when payment is received. This page will refresh automatically.</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Poll for payment status update
            setTimeout(() => {
                location.reload();
            }, 3000);
            break;

        case 'requires_payment_method':
            statusContainer.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-8 h-8 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-left">
                            <h3 class="font-semibold text-red-700 text-lg mb-1">Payment Failed</h3>
                            <p class="text-red-600 text-sm mb-4">Please try another payment method.</p>
                            <a href="{{ route('public.payment.stripe', $paymentIntent) }}" class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Try Again
                            </a>
                        </div>
                    </div>
                </div>
            `;
            break;

        default:
            statusContainer.innerHTML = `
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-8 h-8 text-gray-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-left">
                            <h3 class="font-semibold text-gray-700 text-lg mb-1">Payment Status Unknown</h3>
                            <p class="text-gray-600 text-sm mb-4">Something went wrong. Please contact support if this issue persists.</p>
                            <a href="{{ route('public.payment.stripe', $paymentIntent) }}" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Return to Payment
                            </a>
                        </div>
                    </div>
                </div>
            `;
            break;
    }
});
</script>
@else
<div class="bg-red-50 border border-red-200 rounded-lg p-6">
    <div class="flex items-start space-x-3">
        <svg class="w-8 h-8 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <div class="text-left">
            <h3 class="font-semibold text-red-700 text-lg mb-1">Configuration Error</h3>
            <p class="text-red-600 text-sm">Unable to verify payment. Please contact support.</p>
        </div>
    </div>
</div>
@endif
@endsection

