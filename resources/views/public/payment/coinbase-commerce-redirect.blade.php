@extends('layouts.checkout')

@section('title', 'Redirecting to Payment')

@section('content')
<div class="min-h-screen flex items-center py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="mb-6">
                <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Complete Your Payment</h1>
                <p class="text-gray-600 dark:text-gray-400">You'll be redirected to Coinbase Commerce to complete your payment</p>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                <p class="text-sm text-blue-800 dark:text-blue-200 mb-4">
                    You'll be redirected to Coinbase Commerce to complete your payment securely.
                </p>
                <div class="text-left bg-white dark:bg-gray-700 rounded-lg p-4 mt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Payment ID:</span>
                        <span class="text-sm font-mono text-gray-900 dark:text-white">#{{ $paymentIntent->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($paymentIntent->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <button id="continuePaymentBtn" 
                   class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-lg text-center transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    <span>Continue to Payment</span>
                </button>
                
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    Click the button above to complete your payment with Coinbase Commerce.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const paymentUrl = '{{ $hostedUrl }}';
    
    // Detect if we're in an iframe
    const isInIframe = window.self !== window.top;
    
    // Function to redirect to payment (breaking out of iframe if needed)
    // MUST be called from user interaction (button click) to work in sandboxed iframes
    function redirectToPayment() {
        if (isInIframe) {
            // Break out of iframe and redirect top window
            // This requires user activation (button click) to work in sandboxed iframes
            try {
                // Try to access top window directly
                window.top.location.href = paymentUrl;
            } catch (e) {
                // Cross-origin restriction - use form with target="_top"
                // Form submission also requires user activation
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = paymentUrl;
                form.target = '_top';
                document.body.appendChild(form);
                form.submit();
            }
        } else {
            // Not in iframe, redirect normally in current tab
            window.location.href = paymentUrl;
        }
    }
    
    // Button click handler - user activation is required for iframe navigation
    const continueBtn = document.getElementById('continuePaymentBtn');
    if (continueBtn) {
        continueBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Disable button to prevent double-clicks
            continueBtn.disabled = true;
            continueBtn.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Redirecting...';
            
            // Redirect immediately on user click (user activation)
            redirectToPayment();
        });
    }
    
    // For non-iframe contexts, we can auto-redirect after a delay
    // But for iframes, we MUST wait for user click
    if (!isInIframe) {
        // Auto-redirect after a short delay (only if not in iframe)
        setTimeout(redirectToPayment, 1000);
    }
})();
</script>
@endsection

