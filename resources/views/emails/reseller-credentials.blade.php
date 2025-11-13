<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your IPTV Reseller Credentials - {{ $order->order_number }}</title>
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
                            <p style="margin: 10px 0 0 0; color: #d1fae5; font-size: 16px;">Your Reseller Panel is Ready!</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello {{ $user->name }},</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">Congratulations! Your reseller order <strong>#{{ $order->order_number }}</strong> has been activated and your IPTV reseller panel credentials are ready.</p>
                            
                            @if($panelUrl && $panelUsername && $panelPassword)
                            <!-- Credentials Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0fdf4; border: 2px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <p style="margin: 0 0 20px 0; font-size: 20px; color: #047857; font-weight: bold;">Your IPTV Panel Credentials</p>
                                        
                                        <!-- Panel URL -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 15px 0; background-color: #ffffff; border-radius: 6px; border: 1px solid #d1fae5;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #047857; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Panel URL:</p>
                                                    <p style="margin: 0; font-size: 15px; color: #1e293b; font-family: 'Courier New', monospace; word-break: break-all; background-color: #f8fafc; padding: 12px; border-radius: 4px;">{{ $panelUrl }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Username -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 15px 0; background-color: #ffffff; border-radius: 6px; border: 1px solid #d1fae5;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #047857; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Username:</p>
                                                    <p style="margin: 0; font-size: 15px; color: #1e293b; font-family: 'Courier New', monospace; word-break: break-all; background-color: #f8fafc; padding: 12px; border-radius: 4px;">{{ $panelUsername }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Password -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 15px 0; background-color: #ffffff; border-radius: 6px; border: 1px solid #d1fae5;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #047857; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Password:</p>
                                                    <p style="margin: 0; font-size: 15px; color: #1e293b; font-family: 'Courier New', monospace; word-break: break-all; background-color: #f8fafc; padding: 12px; border-radius: 4px;">{{ $panelPassword }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Access Button -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    <a href="{{ $panelUrl }}" style="display: inline-block; padding: 15px 30px; background-color: #10b981; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;" target="_blank">Access Your IPTV Panel</a>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Security Notice -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fff7ed; border: 2px solid #fed7aa; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 16px; color: #c2410c; font-weight: bold;">Important Security Notice</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #7c2d12; font-size: 14px; line-height: 1.8;">
                                            <li>Keep these credentials secure and confidential</li>
                                            <li>Do not share your login details with unauthorized persons</li>
                                            <li>Change your password regularly for security</li>
                                            <li>Contact support immediately if you suspect unauthorized access</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            @else
                            <!-- Credentials Pending -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #dbeafe; border: 2px solid #3b82f6; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 16px; color: #1e40af; font-weight: bold;">Credentials Setup in Progress</p>
                                        <p style="margin: 0; font-size: 14px; color: #1e40af; line-height: 1.6;">Your IPTV panel credentials are currently being set up by our team. You will receive another email with your access details within 24 hours.</p>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- Credit Pack Information -->
                            @if($creditPack)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #1e40af; font-size: 20px; font-weight: bold;">Your Credit Pack</h3>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 14px; color: #1e40af; font-weight: 600;">Pack Name:</span>
                                                    <span style="font-size: 14px; color: #075985; margin-left: 8px;">{{ $creditPack->name }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 14px; color: #1e40af; font-weight: 600;">Credits Amount:</span>
                                                    <span style="font-size: 14px; color: #075985; margin-left: 8px;">{{ $creditPack->credits_amount }} Credits</span>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        @if($creditPack->features && count($creditPack->features) > 0)
                                        <div style="margin-top: 20px;">
                                            <p style="margin: 0 0 12px 0; font-size: 15px; color: #1e40af; font-weight: 600;">Included Features:</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
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
                            
                            <!-- Getting Started -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">Getting Started as a Reseller</p>
                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #075985; line-height: 1.6;">As a reseller, you now have access to:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                            <li>Your dedicated reseller dashboard</li>
                                            <li>IPTV panel for managing your business</li>
                                            <li>Priority customer support</li>
                                            <li>Marketing materials and resources</li>
                                            <li>Complete control over your IPTV services</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 18px; color: #334155; font-weight: bold;">Need Help?</p>
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #475569;">Our support team is here to help you succeed:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                                            <li>Email us at: {{ $source ? $source->getSupportEmail() : 'contact@smarters-proiptv.com' }}</li>
                                            <li>Reference your order number: <strong>{{ $order->order_number }}</strong></li>
                                            <li>Access our reseller documentation and guides</li>
                                            <li>Contact our technical support team</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 15px; color: #4a5568; line-height: 1.6;">Thank you for choosing {{ $source ? $source->getCompanyName() : config('app.name') }} for your reseller business!</p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #10b981;">{{ $source ? $source->getTeamName() : (config('app.name') . ' Team') }}</strong>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">Reseller Credentials - Order #{{ $order->order_number }}</p>
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ $source ? $source->getCompanyName() : config('app.name') }}. All rights reserved.</p>
                            
                            
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
