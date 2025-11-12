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
                    A popup window will open for you to complete your payment securely with Coinbase Commerce. 
                    Please complete the payment in the popup and return here.
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
                <button id="openPaymentBtn" 
                   class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-lg text-center transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span id="buttonText">Open Payment in Popup</span>
                </button>
                
                <div id="statusMessage" class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    Click the button above to open the payment window.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const paymentUrl = '{{ $hostedUrl }}';
    const paymentIntentId = {{ $paymentIntent->id }};
    const statusCheckUrl = '{{ route("public.payment.coinbase-commerce.status", $paymentIntent) }}';
    let paymentWindow = null;
    let statusCheckInterval = null;
    
    // Function to open payment in popup
    function openPaymentPopup() {
        // Calculate popup dimensions (centered on screen)
        const width = 900;
        const height = 700;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);
        
        // Open popup window
        paymentWindow = window.open(
            paymentUrl,
            'coinbase_payment',
            `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes,toolbar=no,menubar=no,location=no`
        );
        
        if (!paymentWindow) {
            // Popup blocked - show error message
            updateStatus('Popup blocked! Please allow popups for this site and try again.', 'error');
            document.getElementById('openPaymentBtn').disabled = false;
            document.getElementById('buttonText').textContent = 'Retry - Open Payment in Popup';
            return;
        }
        
        // Update UI
        updateStatus('Payment window opened. Please complete your payment in the popup window.', 'info');
        document.getElementById('openPaymentBtn').disabled = true;
        document.getElementById('buttonText').innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Payment Window Open';
        
        // Monitor popup window
        const popupCheckInterval = setInterval(function() {
            if (paymentWindow.closed) {
                clearInterval(popupCheckInterval);
                // Popup closed - check payment status
                updateStatus('Payment window closed. Checking payment status...', 'info');
                checkPaymentStatus();
            }
        }, 1000);
        
        // Also check payment status periodically while popup is open
        statusCheckInterval = setInterval(checkPaymentStatus, 5000); // Every 5 seconds
    }
    
    // Function to check payment status
    function checkPaymentStatus() {
        fetch(statusCheckUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'completed') {
                // Payment completed
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                }
                if (paymentWindow && !paymentWindow.closed) {
                    paymentWindow.close();
                }
                updateStatus('Payment completed successfully! Redirecting...', 'success');
                
                // Redirect to thank you page
                setTimeout(function() {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                }, 1500);
            } else if (data.status === 'failed') {
                // Payment failed
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                }
                updateStatus('Payment failed: ' + (data.message || 'Unknown error'), 'error');
                document.getElementById('openPaymentBtn').disabled = false;
                document.getElementById('buttonText').textContent = 'Retry Payment';
            } else {
                // Still pending
                if (paymentWindow && !paymentWindow.closed) {
                    updateStatus('Waiting for payment confirmation...', 'info');
                }
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
            // Don't show error to user, just log it
        });
    }
    
    // Function to update status message
    function updateStatus(message, type) {
        const statusEl = document.getElementById('statusMessage');
        statusEl.textContent = message;
        
        // Update color based on type
        statusEl.className = 'text-xs text-center';
        if (type === 'error') {
            statusEl.className += ' text-red-600 dark:text-red-400';
        } else if (type === 'success') {
            statusEl.className += ' text-green-600 dark:text-green-400';
        } else {
            statusEl.className += ' text-gray-500 dark:text-gray-400';
        }
    }
    
    // Button click handler
    document.getElementById('openPaymentBtn').addEventListener('click', function() {
        if (!paymentWindow || paymentWindow.closed) {
            openPaymentPopup();
        }
    });
    
    // Auto-open popup after a short delay (better UX)
    setTimeout(function() {
        openPaymentPopup();
    }, 500);
    
    // Clean up intervals when page is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden && statusCheckInterval) {
            clearInterval(statusCheckInterval);
        } else if (!document.hidden && !statusCheckInterval && paymentWindow && !paymentWindow.closed) {
            statusCheckInterval = setInterval(checkPaymentStatus, 5000);
        }
    });
})();
</script>
@endsection

