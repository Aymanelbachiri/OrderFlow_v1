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
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    Redirecting to Coinbase Commerce...
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Auto-submit form to break out of iframe and redirect -->
<!-- This form auto-submits immediately, maintaining user activation from the original form submission -->
<form id="redirectForm" method="GET" action="{{ $hostedUrl }}" target="_top" style="display: none;">
</form>

<script>
(function() {
    const paymentUrl = '{{ $hostedUrl }}';
    
    // Detect if we're in an iframe
    const isInIframe = window.self !== window.top;
    
    // Redirect immediately - user activation from form submission should still be valid
    // Use form submission for iframe (maintains user activation better than window.location)
    if (isInIframe) {
        // In iframe - use form with target="_top" to break out
        // Form submission maintains user activation from the original form click
        const form = document.getElementById('redirectForm') || (function() {
            const f = document.createElement('form');
            f.method = 'GET';
            f.action = paymentUrl;
            f.target = '_top';
            f.style.display = 'none';
            document.body.appendChild(f);
            return f;
        })();
        
        // Submit immediately - this maintains user activation
        form.submit();
    } else {
        // Not in iframe - redirect normally
        window.location.replace(paymentUrl);
    }
})();
</script>
@endsection

