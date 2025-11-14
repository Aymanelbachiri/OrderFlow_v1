<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
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
        .order-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-active { background-color: #d4edda; color: #155724; }
        .status-expired { background-color: #f8d7da; color: #721c24; }
        .status-cancelled { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $company_name ?? config('app.name') }}</h1>
        <h2>Order Confirmation</h2>
    </div>

    <div class="content">
        <p>Hello {{ $order->user->name }},</p>

        <p>Thank you for your order! We're pleased to confirm that we have received your order and it's being processed.</p>

        <div class="order-details">
            <h3>Order Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Order Number:</td>
                    <td style="padding: 8px 0;">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Order Date:</td>
                    <td style="padding: 8px 0;">{{ $order->created_at->format('M d, Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;">
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Amount:</td>
                    <td style="padding: 8px 0; font-size: 18px; font-weight: bold; color: #007bff;">${{ number_format($order->amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Payment Method:</td>
                    <td style="padding: 8px 0; text-transform: capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</td>
                </tr>
                @if($order->starts_at)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Service Start:</td>
                    <td style="padding: 8px 0;">{{ $order->starts_at->format('M d, Y H:i') }}</td>
                </tr>
                @endif
                @if($order->expires_at)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Service Expires:</td>
                    <td style="padding: 8px 0;">{{ $order->expires_at->format('M d, Y H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>

        @if($order->pricingPlan)
        <div class="order-details">
            <h3>Service Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Plan:</td>
                    <td style="padding: 8px 0;">{{ $order->pricingPlan->display_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Server Type:</td>
                    <td style="padding: 8px 0; text-transform: capitalize;">{{ $order->pricingPlan->server_type }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Device Count:</td>
                    <td style="padding: 8px 0;">{{ $order->pricingPlan->device_count }} device(s)</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Duration:</td>
                    <td style="padding: 8px 0;">{{ $order->pricingPlan->duration_months }} month(s)</td>
                </tr>
            </table>

            @if($order->pricingPlan->features)
            <h4>Plan Features:</h4>
            <ul>
                @foreach($order->pricingPlan->features as $feature)
                <li>{{ $feature }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endif

        @if($order->status === 'pending')
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">Next Steps</h4>
            <p style="color: #856404; margin-bottom: 0;">Your order is currently pending payment. You will receive payment instructions shortly, or you can log in to your account to complete the payment process.</p>
        </div>
        @elseif($order->status === 'active')
        <div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h4 style="margin-top: 0; color: #155724;">Service Active</h4>
            <p style="color: #155724; margin-bottom: 0;">Your IPTV service is now active! You will receive your connection credentials shortly via email.</p>
        </div>
        @endif

        <!-- Access Your Account button removed -->

        <h3>What's Next?</h3>
        <ol>
            @if($order->status === 'pending')
            <li>Complete your payment using the instructions you'll receive</li>
            <li>Once payment is confirmed, your service will be activated</li>
            <li>You'll receive your IPTV connection credentials via email</li>
            @else
            <li>Check your email for IPTV connection credentials</li>
            <li>Download and install your preferred IPTV player</li>
            <li>Enter your credentials and start enjoying your service</li>
            @endif
            <li>Contact support if you need any assistance</li>
        </ol>

        <p>If you have any questions about your order or need assistance, please don't hesitate to contact our support team.</p>

        <p>Thank you for choosing {{ $company_name ?? config('app.name') }}!</p>

        <p>Best regards,<br>
        The {{ $team_name ?? ($company_name ?? config('app.name')) . ' Team' }}</p>
    </div>

    <div class="footer">
        <p>Order #{{ $order->order_number }} | {{ $order->created_at->format('M d, Y') }}</p>
        <p>&copy; {{ date('Y') }} {{ $company_name ?? config('app.name') }}. All rights reserved.</p>
        @if(isset($website) && $website)
        <p><a href="{{ $website }}" style="color: #6c757d;">{{ $website }}</a></p>
        @endif
        @if(isset($contact_email) && $contact_email)
        <p>Contact: <a href="mailto:{{ $contact_email }}" style="color: #6c757d;">{{ $contact_email }}</a></p>
        @endif
        @if(isset($phone_number) && $phone_number)
        <p>Phone: {{ $phone_number }}</p>
        @endif
    </div>
</body>
</html>
