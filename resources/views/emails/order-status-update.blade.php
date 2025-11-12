<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header with status-specific color -->
                    <tr>
                        <td style="background-color: {{ $order->status === 'active' ? '#10b981' : ($order->status === 'pending' ? '#f59e0b' : ($order->status === 'expired' ? '#ef4444' : '#6b7280')) }}; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">{{ config('app.name') }}</h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Order Status Update</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello {{ $order->user->name }},</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">We're writing to inform you about an update to your order status.</p>
                            
                            <!-- Status Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: {{ $order->status === 'active' ? '#d1fae5' : ($order->status === 'pending' ? '#fef3c7' : ($order->status === 'expired' ? '#fee2e2' : '#e5e7eb')) }}; border: 2px solid {{ $order->status === 'active' ? '#10b981' : ($order->status === 'pending' ? '#f59e0b' : ($order->status === 'expired' ? '#ef4444' : '#6b7280')) }}; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px; text-align: center;">
                                        <p style="margin: 0 0 10px 0; font-size: 22px; color: {{ $order->status === 'active' ? '#047857' : ($order->status === 'pending' ? '#b45309' : ($order->status === 'expired' ? '#b91c1c' : '#374151')) }}; font-weight: bold;">
                                            Your Order Status: {{ ucfirst($order->status) }}
                                        </p>
                                        <p style="margin: 0; font-size: 16px; color: {{ $order->status === 'active' ? '#065f46' : ($order->status === 'pending' ? '#92400e' : ($order->status === 'expired' ? '#991b1b' : '#4b5563')) }}; font-weight: 600;">
                                            Order #{{ $order->order_number }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Status-specific message -->
                            @if($order->status === 'active')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #d1fae5; border-left: 4px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 18px; color: #047857; font-weight: bold;">🎉 Great News! Your Service is Now Active</p>
                                        <p style="margin: 0; font-size: 14px; color: #065f46; line-height: 1.6;">
                                            @if($order->order_type === 'credit_pack' || ($order->pricingPlan && $order->pricingPlan->plan_type === 'reseller'))
                                                Your reseller panel has been activated and is ready to use. You should receive your panel credentials shortly if you haven't already.
                                            @else
                                                Your IPTV service has been activated and is ready to use. You should receive your connection credentials shortly if you haven't already.
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @elseif($order->status === 'pending')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 18px; color: #b45309; font-weight: bold;">⏳ Order Pending</p>
                                        <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 1.6;">Your order is currently pending. This may be due to payment processing or manual review. We'll notify you once it's processed.</p>
                                    </td>
                                </tr>
                            </table>
                            @elseif($order->status === 'expired')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fee2e2; border-left: 4px solid #ef4444; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 18px; color: #b91c1c; font-weight: bold;">⚠️ Service Expired</p>
                                        <p style="margin: 0; font-size: 14px; color: #991b1b; line-height: 1.6;">Your service has expired. To continue enjoying our service, please renew your subscription or purchase a new credit pack.</p>
                                    </td>
                                </tr>
                            </table>
                            @elseif($order->status === 'cancelled')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #e5e7eb; border-left: 4px solid #6b7280; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 18px; color: #374151; font-weight: bold;">❌ Order Cancelled</p>
                                        <p style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.6;">Your order has been cancelled. If you believe this is an error, please contact our support team.</p>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- Order Details -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 20px 0; font-size: 18px; color: #334155; font-weight: bold;">Order Details</p>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">Order Number:</td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">
                                                    @if($order->order_type === 'credit_pack')
                                                        Credit Pack:
                                                    @else
                                                        Service:
                                                    @endif
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right;">
                                                    @if($order->order_type === 'credit_pack' && $order->resellerCreditPack)
                                                        {{ $order->resellerCreditPack->name }}
                                                    @else
                                                        {{ $order->pricingPlan->display_name ?? 'IPTV Service' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($order->order_type === 'credit_pack' && $order->resellerCreditPack)
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">Credits:</td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right;">{{ number_format($order->resellerCreditPack->credits_amount) }} Credits</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">Amount:</td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right; font-weight: 600;">${{ number_format($order->amount, 2) }}</td>
                                            </tr>
                                            @if($order->order_type === 'subscription')
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">Subscription Type:</td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right;">{{ $order->subscription_type_display }}</td>
                                            </tr>
                                            @endif
                                            @if($order->starts_at)
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">Service Start:</td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right;">{{ $order->starts_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endif
                                            @if($order->expires_at)
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #475569; font-weight: 600;">
                                                    @if($order->status === 'expired')
                                                        Expired On:
                                                    @else
                                                        Expires On:
                                                    @endif
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1e293b; text-align: right;">{{ $order->expires_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- What's Next Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        @if($order->status === 'active')
                                            <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">What's Next?</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                                @if($order->order_type === 'credit_pack' || ($order->pricingPlan && $order->pricingPlan->plan_type === 'reseller'))
                                                    <li>Check your email for IPTV panel access credentials</li>
                                                    <li>Log in to your reseller panel</li>
                                                    <li>Start managing your IPTV services</li>
                                                    <li>Contact support if you need any assistance</li>
                                                @else
                                                    <li>Check your email for IPTV connection credentials</li>
                                                    <li>Download your preferred IPTV player application</li>
                                                    <li>Enter your credentials and start streaming</li>
                                                    <li>Contact support if you need any assistance</li>
                                                @endif
                                            </ul>
                                        @elseif($order->status === 'pending')
                                            <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">What's Next?</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                                <li>We're processing your order and will update you soon</li>
                                                <li>Check your email for any payment instructions</li>
                                                <li>Contact support if you have any questions</li>
                                            </ul>
                                        @elseif($order->status === 'expired')
                                            <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">Renew Your Service</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                                <li>Log in to your account to renew your subscription</li>
                                                <li>Choose from our available plans</li>
                                                <li>Your service will be reactivated after payment</li>
                                            </ul>
                                        @elseif($order->status === 'cancelled')
                                            <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">Need Help?</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                                <li>Contact our support team if you have questions</li>
                                                <li>We can help you place a new order if needed</li>
                                                <li>Check our FAQ for common questions</li>
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA Button Removed -->
                            
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 14px; color: #4a5568; line-height: 1.6;">If you have any questions or concerns about this status update, please don't hesitate to contact our support team.</p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #3b82f6;">The {{ config('app.name') }} Team</strong>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">Order #{{ $order->order_number }} | Status: {{ ucfirst($order->status) }}</p>
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                            
                        
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
