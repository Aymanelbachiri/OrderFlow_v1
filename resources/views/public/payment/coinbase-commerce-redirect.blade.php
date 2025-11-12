<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Continue to Payment</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            color: white;
            padding: 2rem;
        }
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Auto-submit form as backup method -->
    <form id="redirectForm" method="GET" action="{{ $hostedUrl }}" target="_top" style="display: none;"></form>
    
    <div class="container">
        <div class="spinner"></div>
        <h1 style="margin: 0 0 1rem; font-size: 1.5rem;">Continue to Payment</h1>
        <p style="margin: 0; opacity: 0.9; margin-bottom: 2rem;">Click the button below to complete your payment with Coinbase Commerce</p>
        <div style="margin-bottom: 2rem; font-size: 0.875rem; opacity: 0.8; background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
            <p style="margin: 0.5rem 0;">Payment ID: #{{ $paymentIntent->id }}</p>
            <p style="margin: 0.5rem 0;">Amount: ${{ number_format($paymentIntent->amount, 2) }}</p>
        </div>
        <button id="continueButton" onclick="submitPaymentForm()" style="background: white; color: #667eea; border: none; padding: 1rem 2rem; font-size: 1rem; font-weight: 600; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
            Continue to Payment
        </button>
        <p style="margin-top: 1.5rem; font-size: 0.75rem; opacity: 0.6;">
            You will be redirected to Coinbase Commerce to complete your payment securely
        </p>
    </div>

    <script>
    // Function to submit payment form - called by button click
    // This maintains user activation (gesture) required for sandboxed iframes
    function submitPaymentForm() {
        var paymentUrl = '{{ $hostedUrl }}';
        var isInIframe = window.self !== window.top;
        
        // Disable button to prevent double-clicks
        var button = document.getElementById('continueButton');
        if (button) {
            button.disabled = true;
            button.textContent = 'Redirecting...';
            button.style.opacity = '0.7';
            button.style.cursor = 'not-allowed';
        }
        
        if (isInIframe) {
            // In iframe - use form with target="_top" to break out
            // User activation from button click is preserved
            var form = document.getElementById('redirectForm');
            if (form) {
                try {
                    // Form has target="_top" - submit it to break out of iframe
                    form.submit();
                } catch (e) {
                    // Fallback: try direct redirect
                    try {
                        window.top.location.href = paymentUrl;
                    } catch (e2) {
                        // Last resort: create new form
                        var newForm = document.createElement('form');
                        newForm.method = 'GET';
                        newForm.action = paymentUrl;
                        newForm.target = '_top';
                        newForm.style.cssText = 'position:absolute;left:-9999px;';
                        document.body.appendChild(newForm);
                        newForm.submit();
                    }
                }
            } else {
                // Form not found, create it
                var newForm = document.createElement('form');
                newForm.method = 'GET';
                newForm.action = paymentUrl;
                newForm.target = '_top';
                newForm.style.cssText = 'position:absolute;left:-9999px;';
                document.body.appendChild(newForm);
                newForm.submit();
            }
        } else {
            // Not in iframe - redirect normally
            window.location.replace(paymentUrl);
        }
    }
    
    // Auto-submit if not in iframe (no user activation needed)
    (function() {
        var isInIframe = window.self !== window.top;
        if (!isInIframe) {
            // Not in iframe - redirect immediately
            window.location.replace('{{ $hostedUrl }}');
        }
    })();
    </script>
</body>
</html>
