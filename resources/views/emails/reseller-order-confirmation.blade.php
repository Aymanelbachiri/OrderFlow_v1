<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseller Panel Account Setup - {{ $order->order_number }}</title>
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
                            <p style="margin: 10px 0 0 0; color: #e0e7ff; font-size: 16px;">Reseller Panel Account Setup</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello {{ $user->name }},</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">Thank you for your reseller credit pack purchase! We've received your order and payment confirmation.</p>
                            
                            <!-- 24-Hour Setup Notice -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #dbeafe; border: 2px solid #3b82f6; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 16px; color: #1e40af; font-weight: bold;">⏱️ Panel Account Creation in Progress</p>
                                        <p style="margin: 0; font-size: 14px; color: #1e40af; line-height: 1.6;">Your IPTV panel account will be created within 24 hours. You will receive your panel access credentials via email once your account is ready.</p>
                                    </td>
                                </tr>
                            </table>
                            
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
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Order ID</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->id }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
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
                                            </tr>
                                            <tr>
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
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Status</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ ucfirst($order->status) }}</span>
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
                                                                <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Amount Paid</span>
                                                                <span style="font-size: 15px; color: #2d3748; font-weight: 600;">${{ number_format($order->amount, 2) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
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
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Credit Pack Information -->
                            @if($creditPack)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0fdf4; border-left: 4px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #047857; font-size: 20px; font-weight: bold;">Credit Pack Information</h3>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 14px; color: #065f46; font-weight: 600;">Pack Name:</span>
                                                    <span style="font-size: 14px; color: #047857; margin-left: 8px;">{{ $creditPack->name }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 14px; color: #065f46; font-weight: 600;">Credits Amount:</span>
                                                    <span style="font-size: 14px; color: #047857; margin-left: 8px;">{{ $creditPack->credits_amount }} Credits</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 14px; color: #065f46; font-weight: 600;">Price:</span>
                                                    <span style="font-size: 14px; color: #047857; margin-left: 8px;">${{ number_format($creditPack->price, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 14px; color: #065f46; font-weight: 600;">Price Per Credit:</span>
                                                    <span style="font-size: 14px; color: #047857; margin-left: 8px;">{{ $creditPack->formatted_price_per_credit }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        @if($creditPack->features && count($creditPack->features) > 0)
                                        <div style="margin-top: 20px;">
                                            <p style="margin: 0 0 12px 0; font-size: 15px; color: #065f46; font-weight: 600;">Included Features:</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #047857; font-size: 14px; line-height: 1.8;">
                                                @foreach($creditPack->features as $feature)
                                                <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                        
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- What Happens Next -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">What Happens Next?</p>
                                        <ol style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                            <li style="margin-bottom: 8px;"><strong>Payment Verification:</strong> We're verifying your payment details (completed)</li>
                                            <li style="margin-bottom: 8px;"><strong>Panel Account Setup:</strong> Our team will create your IPTV panel account within 24 hours</li>
                                            <li style="margin-bottom: 8px;"><strong>Credentials Delivery:</strong> You'll receive an email with your panel login details and access URL</li>
                                            <li><strong>Account Activation:</strong> Once you receive your credentials, you can start managing your IPTV services!</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 18px; color: #334155; font-weight: bold;">Need Help?</p>
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #475569;">If you have any questions about your reseller account or need assistance:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                                            <li>Email us at: contact@smarters-proiptv.com</li>
                                            <li>Reference your order number: <strong>{{ $order->order_number }}</strong></li>
                                            <li>Check our reseller documentation and guides</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Important Notes -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fff7ed; border: 2px solid #fed7aa; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 16px; color: #c2410c; font-weight: bold;">Important Information</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #7c2d12; font-size: 14px; line-height: 1.8;">
                                            <li>Keep this email for your records</li>
                                            <li>Your order ID is: <strong>{{ $order->id }}</strong></li>
                                            <li>Panel credentials will be sent to: <strong>{{ $user->email }}</strong></li>
                                            <li>Expected delivery: Within 24 hours</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 15px; color: #4a5568; line-height: 1.6;">Thank you for becoming a reseller partner with {{ config('app.name') }}. We're excited to work with you!</p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #4c51bf;">The {{ config('app.name') }} Team</strong>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">Order #{{ $order->order_number }} | Reseller Credit Pack</p>
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                            
                            
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

