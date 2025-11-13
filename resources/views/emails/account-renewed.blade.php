<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Renewed - {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #10b981; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">{{ $source ? $source->getCompanyName() : config('app.name') }}</h1>
                            <p style="margin: 10px 0 0 0; color: #d1fae5; font-size: 16px;">Account Renewed Successfully</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello {{ $customer->name }},</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">Great news! Your IPTV subscription has been successfully renewed and activated. Your service will continue without interruption.</p>
                            
                            <!-- Success Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #d1fae5; border: 2px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 8px 0; font-size: 16px; color: #065f46; font-weight: bold;">✓ Renewal Activated</p>
                                        <p style="margin: 0; font-size: 14px; color: #047857; line-height: 1.6;">Your account has been renewed and is now active. You can continue using your service immediately.</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Order Details Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f7fafc; border-left: 4px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #10b981; font-size: 20px; font-weight: bold;">Renewal Details</h3>
                                        
                                        <!-- Order Info Grid -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Renewal Order</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->order_number }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Renewal Date</span>
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
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Expires</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->expires_at ? $order->expires_at->format('M d, Y') : 'N/A' }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if($originalOrder)
                                            <tr>
                                                <td colspan="2" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Previous Order</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $originalOrder->order_number }}</span>
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
                            
                            @if($order->subscription_username || $order->subscription_password || $order->subscription_url)
                            <!-- Credentials Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">Your Account Credentials</p>
                                        @if($order->subscription_username)
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #075985;"><strong>Username:</strong> {{ $order->subscription_username }}</p>
                                        @endif
                                        @if($order->subscription_password)
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #075985;"><strong>Password:</strong> {{ $order->subscription_password }}</p>
                                        @endif
                                        @if($order->subscription_url)
                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #075985;"><strong>Server URL:</strong> <a href="{{ $order->subscription_url }}" style="color: #0369a1; text-decoration: underline;">{{ $order->subscription_url }}</a></p>
                                        @endif
                                        <p style="margin: 0; font-size: 13px; color: #0c4a6e; line-height: 1.6;">Please keep these credentials secure. Use them to access your IPTV service.</p>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- What's Next -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">Your Service is Active!</p>
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #075985; line-height: 1.6;">Your subscription has been renewed and your service continues without interruption. You can start using your IPTV service immediately.</p>
                                        <p style="margin: 0; font-size: 14px; color: #075985; line-height: 1.6;">Your service will remain active until <strong>{{ $order->expires_at ? $order->expires_at->format('M d, Y') : 'N/A' }}</strong>.</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Need Help -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 18px; color: #334155; font-weight: bold;">Need Help?</p>
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #475569;">If you have any questions about your renewal, please don't hesitate to contact us:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                                            <li>Email: {{ $source ? $source->getSupportEmail() : 'contact@smarters-proiptv.com' }}</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 14px; color: #cbd5e1; font-weight: 600;">Thank you for choosing {{ $source ? $source->getCompanyName() : config('app.name') }}!</p>
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">This is an automated message. Please do not reply to this email.</p>
                            <p style="margin: 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ $source ? $source->getCompanyName() : config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

