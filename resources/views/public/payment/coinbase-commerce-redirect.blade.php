<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Redirecting to Payment</title>
    <!-- CRITICAL: Break out of iframe IMMEDIATELY using document.write -->
    <!-- This replaces the entire page immediately, before anything else loads -->
    <script>
    // Execute IMMEDIATELY - must be first thing in head
    // ALWAYS break out to top window for Coinbase Commerce payment link
    (function() {
        'use strict';
        var paymentUrl = '{{ $hostedUrl }}';
        var isInIframe = window.self !== window.top;
        
        // Always use break-out method to ensure payment opens in top window
        if (isInIframe) {
            // In iframe - MUST break out immediately to top window
            // Use document.write to replace page with auto-submitting form with target="_top"
            // This maintains user activation from original form submission
            try {
                // Method 1: Try direct redirect first (fastest if allowed)
                window.top.location.href = paymentUrl;
            } catch (e) {
                // Method 2: Cross-origin blocked - use document.write to create auto-submit form
                // Form with target="_top" will break out of iframe and open in top window
                document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting...</title></head><body><form id="breakoutForm" method="GET" action="' + paymentUrl + '" target="_top"></form><script>document.getElementById("breakoutForm").submit();<\/script></body></html>');
                document.close();
            }
        } else {
            // Not in iframe - redirect normally in current window
            window.location.replace(paymentUrl);
        }
    })();
    </script>
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
        <h1 style="margin: 0 0 1rem; font-size: 1.5rem;">Redirecting to Payment</h1>
        <p style="margin: 0; opacity: 0.9;">Please wait while we redirect you to Coinbase Commerce...</p>
        <div style="margin-top: 2rem; font-size: 0.875rem; opacity: 0.8;">
            <p>Payment ID: #{{ $paymentIntent->id }}</p>
            <p>Amount: ${{ number_format($paymentIntent->amount, 2) }}</p>
        </div>
        <p style="margin-top: 2rem; font-size: 0.875rem; opacity: 0.7;">
            If you are not redirected automatically, 
            <a href="{{ $hostedUrl }}" target="_top" style="color: white; text-decoration: underline;">click here</a>.
        </p>
    </div>

    <script>
    // Backup: Submit form immediately when body is available
    // This ensures form submission with target="_top" happens even if head script didn't work
    (function() {
        'use strict';
        var paymentUrl = '{{ $hostedUrl }}';
        var isInIframe = window.self !== window.top;
        
        if (isInIframe) {
            // Submit form with target="_top" immediately - this breaks out of iframe
            // This should have already happened in head, but do it again as backup
            var form = document.getElementById('redirectForm');
            if (form) {
                try {
                    // Form already has target="_top" - submit it to break out
                    form.submit();
                } catch (e) {
                    // Create new form with target="_top" if submit fails
                    var newForm = document.createElement('form');
                    newForm.method = 'GET';
                    newForm.action = paymentUrl;
                    newForm.target = '_top'; // CRITICAL: target="_top" breaks out of iframe
                    newForm.style.cssText = 'position:absolute;left:-9999px;';
                    document.body.appendChild(newForm);
                    newForm.submit();
                }
            } else {
                // Form not found, create it with target="_top"
                var newForm = document.createElement('form');
                newForm.id = 'redirectForm';
                newForm.method = 'GET';
                newForm.action = paymentUrl;
                newForm.target = '_top'; // CRITICAL: target="_top" breaks out of iframe
                newForm.style.cssText = 'position:absolute;left:-9999px;';
                document.body.appendChild(newForm);
                newForm.submit();
            }
        }
    })();
    </script>
</body>
</html>
