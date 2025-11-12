@extends('layouts.checkout')

@section('title', 'Payment Pending')

@section('content')
<div class="min-h-screen flex items-center py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="mb-6">
                <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Payment Pending</h1>
                <p class="text-gray-600 dark:text-gray-400">Your crypto payment is being processed</p>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    We're waiting for confirmation of your payment on the blockchain. This usually takes a few minutes.
                    You'll receive an email confirmation once your payment is confirmed.
                </p>
            </div>

            <div class="space-y-4">
                <div class="text-left bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Payment ID:</span>
                        <span class="text-sm font-mono text-gray-900 dark:text-white">#{{ $paymentIntent->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($paymentIntent->amount, 2) }}</span>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button id="refreshStatusBtn" 
                       class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="refreshText">Refresh Status</span>
                        <span id="refreshSpinner" class="hidden">Checking...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let checkInterval = null;
let isChecking = false;

function checkPaymentStatus() {
    if (isChecking) return;
    
    isChecking = true;
    const refreshBtn = document.getElementById('refreshStatusBtn');
    const refreshText = document.getElementById('refreshText');
    const refreshSpinner = document.getElementById('refreshSpinner');
    
    refreshBtn.disabled = true;
    refreshText.classList.add('hidden');
    refreshSpinner.classList.remove('hidden');
    
    fetch('{{ route("public.payment.coinbase-commerce.status", $paymentIntent) }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'completed') {
            // Payment completed - redirect to thank you page
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
            } else {
                window.location.reload();
            }
        } else if (data.status === 'failed') {
            // Payment failed
            alert('Payment failed: ' + (data.message || 'Unknown error'));
            window.location.href = '{{ route("checkout.show") }}';
        } else {
            // Still pending
            refreshBtn.disabled = false;
            refreshText.classList.remove('hidden');
            refreshSpinner.classList.add('hidden');
            isChecking = false;
        }
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
        refreshBtn.disabled = false;
        refreshText.classList.remove('hidden');
        refreshSpinner.classList.add('hidden');
        isChecking = false;
    });
}

// Check status on page load
checkPaymentStatus();

// Auto-check every 10 seconds
checkInterval = setInterval(checkPaymentStatus, 10000);

// Manual refresh button
document.getElementById('refreshStatusBtn').addEventListener('click', function() {
    if (!isChecking) {
        checkPaymentStatus();
    }
});

// Clean up interval when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
    } else {
        if (!checkInterval) {
            checkInterval = setInterval(checkPaymentStatus, 10000);
        }
    }
});
</script>
@endsection

