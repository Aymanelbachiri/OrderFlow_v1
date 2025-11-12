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
    // Use document.write to replace entire page with auto-submitting form
    (function() {
        'use strict';
        var paymentUrl = '{{ $hostedUrl }}';
        var isInIframe = window.self !== window.top;
        
        if (isInIframe) {
            // In iframe - MUST break out immediately
            // Use document.write to replace page with auto-submitting form
            // This maintains user activation from original form submission
            try {
                // Try direct redirect first (fastest if allowed)
                window.top.location.href = paymentUrl;
            } catch (e) {
                // Cross-origin blocked - use document.write to create auto-submit form
                // This MUST work - form submission with target="_top" breaks out of iframe
                document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting...</title></head><body><form id="breakoutForm" method="GET" action="' + paymentUrl + '" target="_top"></form><script>document.getElementById("breakoutForm").submit();<\/script></body></html>');
                document.close();
            }
        } else {
            // Not in iframe - redirect normally
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
    // This ensures form submission happens even if head script didn't work
    (function() {
        'use strict';
        var paymentUrl = '{{ $hostedUrl }}';
        var isInIframe = window.self !== window.top;
        
        if (isInIframe) {
            // Submit form immediately - this should have already happened in head
            // But do it again as backup
            var form = document.getElementById('redirectForm');
            if (form) {
                try {
                    form.submit();
                } catch (e) {
                    // Create new form if submit fails
                    var newForm = document.createElement('form');
                    newForm.method = 'GET';
                    newForm.action = paymentUrl;
                    newForm.target = '_top';
                    newForm.style.cssText = 'position:absolute;left:-9999px;';
                    document.body.appendChild(newForm);
                    newForm.submit();
                }
            }
        }
    })();
    </script>
</body>
</html>
