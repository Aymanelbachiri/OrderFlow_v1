<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Instructions - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e9ecef;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: #6c757d;
        }
        .payment-box {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .order-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .button:hover {
            background-color: #218838;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>Payment Instructions</h2>
    </div>

    <div class="content">
        <p>Hello {{ $order->user->name }},</p>

        <p>Thank you for your order! To activate your IPTV service, please complete your payment using the instructions below.</p>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 5px 0; font-weight: bold;">Order Number:</td>
                    <td style="padding: 5px 0;">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; font-weight: bold;">Service:</td>
                    <td style="padding: 5px 0;">{{ $order->pricingPlan->display_name ?? 'IPTV Service' }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; font-weight: bold;">Duration:</td>
                    <td style="padding: 5px 0;">{{ $order->pricingPlan->duration_months ?? 1 }} month(s)</td>
                </tr>
            </table>
        </div>

        <div class="amount">
            Total Amount: ${{ number_format($order->amount, 2) }}
        </div>

        <div class="payment-box">
            <h3>Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</h3>
            
            @if($order->payment_method === 'paypal')
                <p>You can complete your payment by clicking the secure PayPal payment link below:</p>
                <div style="text-align: center;">
                    <a href="{{ $paymentUrl }}" class="button">Pay with PayPal - ${{ number_format($order->amount, 2) }}</a>
                </div>
                <p><small>This link will take you to a secure PayPal payment page where you can complete your transaction.</small></p>
                
            @elseif($order->payment_method === 'stripe')
                <p>Complete your payment securely with Stripe:</p>
                <div style="text-align: center;">
                    <a href="{{ $paymentUrl }}" class="button">Pay with Stripe - ${{ number_format($order->amount, 2) }}</a>
                </div>
                <p><small>You'll be redirected to Stripe's secure payment page to complete your transaction with credit/debit card.</small></p>
                
            @elseif($order->payment_method === 'paypal')
                <p>Complete your payment with PayPal:</p>
                <div style="text-align: center;">
                    <a href="{{ $paymentUrl }}" class="button">Pay with PayPal - ${{ number_format($order->amount, 2) }}</a>
                </div>
                <p><small>You'll be redirected to PayPal to complete your payment securely.</small></p>
                
            @elseif($order->payment_method === 'crypto')
                <p>Pay with USDT(TRC20):</p>
                <div style="text-align: center;">
                    <a href="{{ $paymentUrl }}" class="button">Pay with USDT(TRC20) - ${{ number_format($order->amount, 2) }}</a>
                </div>
                <p><small>You'll be provided with wallet addresses and payment instructions for Bitcoin, Ethereum, or other supported cryptocurrencies.</small></p>
                
            @else
                <p>Please contact our support team to complete your payment for this order.</p>
                <div style="text-align: center;">
                    <a href="{{ $loginUrl }}" class="button">Contact Support</a>
                </div>
            @endif
        </div>

        <div class="warning">
            <h4>Important Payment Information:</h4>
            <ul>
                <li><strong>Payment Deadline:</strong> Please complete your payment within 48 hours to avoid order cancellation</li>
                <li><strong>Service Activation:</strong> Your IPTV service will be activated immediately after payment confirmation</li>
                <li><strong>Credentials:</strong> You'll receive your IPTV connection details via email once payment is processed</li>
                <li><strong>Support:</strong> Contact us if you encounter any issues during the payment process</li>
            </ul>
        </div>

        <h3>After Payment:</h3>
        <ol>
            <li>You'll receive a payment confirmation email</li>
            <li>Your IPTV service will be activated within minutes</li>
            <li>Connection credentials will be sent to your email</li>
            <li>You can start enjoying your IPTV service immediately</li>
        </ol>

        <h3>Need Help?</h3>
        <p>If you have any questions about payment or need assistance:</p>
        <ul>
            <li>Log in to your account: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></li>
            <li>Contact our support team</li>
            <li>Check our FAQ section for common questions</li>
        </ul>

        <p>Thank you for choosing {{ config('app.name') }}. We look forward to providing you with excellent IPTV service!</p>

        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>Order #{{ $order->order_number }} | Amount: ${{ number_format($order->amount, 2) }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        
        @if(config('app.url'))
        <p>
            <a href="{{ config('app.url') }}" style="color: #007bff;">Visit our website</a>
        </p>
        @endif
        
        <p style="font-size: 12px; color: #999;">
            This email was sent to {{ $order->user->email }} regarding order {{ $order->order_number }}.
        </p>
    </div>
</body>
</html>
