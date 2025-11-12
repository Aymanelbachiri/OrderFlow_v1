<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4c51bf; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">{{ config('app.name') }}</h1>
                            <p style="margin: 10px 0 0 0; color: #e0e7ff; font-size: 16px;">Order Confirmation</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello {{ $customer->name }},</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">Thank you for your order! We've received your IPTV subscription request and it's currently being processed.</p>
                            
                            <!-- Order Details Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f7fafc; border-left: 4px solid #4c51bf; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #4c51bf; font-size: 20px; font-weight: bold;">Order Details</h3>
                                        
                                        <!-- Order Info Grid -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Order Number</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->order_number }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Order Date</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->created_at->format('M d, Y') }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Service Plan</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $plan->display_name }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Amount</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">${{ number_format($order->amount, 2) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Payment Method</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Duration</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $plan->duration_months }} month(s)</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if($order->order_type === 'subscription')
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Subscription Type</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->subscription_type_display }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Device Count</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $plan->device_count }} device(s)</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Status Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fff7ed; border: 2px solid #fed7aa; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 8px 0; font-size: 16px; color: #c2410c; font-weight: bold;">Order Status: Processing</p>
                                        <p style="margin: 0; font-size: 14px; color: #7c2d12; line-height: 1.6;">Your order is currently being processed by our team. You will receive your IPTV credentials via email once your subscription has been activated.</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- What Happens Next -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">What Happens Next?</p>
                                        <ol style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                            <li style="margin-bottom: 8px;"><strong>Payment Processing:</strong> We're verifying your payment details</li>
                                            <li style="margin-bottom: 8px;"><strong>Account Setup:</strong> Our team will set up your IPTV service with {{ $plan->device_count }} device credential(s)</li>
                                            <li style="margin-bottom: 8px;"><strong>Credentials Delivery:</strong> You'll receive an email with your login details and setup instructions</li>
                                            <li><strong>Service Activation:</strong> Your IPTV service will be ready to use!</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            @if($plan->features)
                            <!-- Plan Features -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 18px; color: #334155; font-weight: bold;">Your Plan Features:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                                            @foreach($plan->features as $feature)
                                            <li style="margin-bottom: 8px;">{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 35px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('public.thank-you', $order) }}" style="display: inline-block; padding: 16px 40px; background-color: #4c51bf; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">View Your Order</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Need Help -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 18px; color: #334155; font-weight: bold;">Need Help?</p>
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #475569;">If you have any questions about your order, please don't hesitate to contact us:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                                            <li>Email: contact@smarters-proiptv.com</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Important Notice -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #eef2ff; border: 2px solid #c7d2fe; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; font-size: 14px; color: #4c51bf; line-height: 1.6;"><strong>Important:</strong> Please keep this email for your records. Your order number is <strong>{{ $order->order_number }}</strong>.</p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 14px; color: #cbd5e1; font-weight: 600;">Thank you for choosing {{ config('app.name') }}!</p>
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">This is an automated message. Please do not reply to this email.</p>
                            <p style="margin: 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>