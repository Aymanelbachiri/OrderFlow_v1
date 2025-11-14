<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your IPTV Service Credentials</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4c51bf; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">{{ $company_name ?? config('app.name') }}</h1>
                            <p style="margin: 10px 0 0 0; color: #e0e7ff; font-size: 16px;">Your IPTV Service Credentials</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello {{ $client->name }},</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">Your IPTV services are now active! Below you'll find the credentials and connection details for your active subscriptions.</p>
                            
                            @foreach($orders as $order)
                            <!-- Service Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f7fafc; border-left: 4px solid #4c51bf; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #4c51bf; font-size: 20px; font-weight: bold;">{{ $order->pricingPlan->display_name ?? 'IPTV Service' }}</h3>
                                        
                                        <!-- Order Info -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Order Number</span><br>
                                                    <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->order_number }}</span>
                                                </td>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Status</span><br>
                                                    <span style="font-size: 15px; color: #10b981; font-weight: 600;">{{ ucfirst($order->status) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;" colspan="2">
                                                    <span style="font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Expires</span><br>
                                                    <span style="font-size: 15px; color: #2d3748; font-weight: 600;">{{ $order->expires_at ? $order->expires_at->format('M d, Y') : 'No expiry' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        @if($order->devices && count($order->devices) > 0)
                                            @foreach($order->devices as $device)
                                            <!-- Credentials Box -->
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 15px 0; background-color: #eef2ff; border-radius: 6px; border: 1px solid #c7d2fe;">
                                                <tr>
                                                    <td style="padding: 20px;">
                                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #4c51bf; font-weight: bold; text-transform: uppercase;">Device {{ ($device['device_number'] ?? 0) + 1 }} Connection Details</p>
                                                        
                                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                            <tr>
                                                                <td style="padding: 8px 0;">
                                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Server URL:</span><br>
                                                                    <span style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $device['url'] }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 8px 0;">
                                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Username:</span><br>
                                                                    <span style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $device['username'] }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 8px 0;">
                                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Password:</span><br>
                                                                    <span style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $device['password'] }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            @endforeach
                                        @else
                                            <!-- Single Credentials Box -->
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 15px 0; background-color: #eef2ff; border-radius: 6px; border: 1px solid #c7d2fe;">
                                                <tr>
                                                    <td style="padding: 20px;">
                                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #4c51bf; font-weight: bold; text-transform: uppercase;">Connection Details</p>
                                                        
                                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                            <tr>
                                                                <td style="padding: 8px 0;">
                                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Server URL:</span><br>
                                                                    <span style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $order->subscription_url ?? 'http://your-server.com:8080' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 8px 0;">
                                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Username:</span><br>
                                                                    <span style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $order->subscription_username ?? $client->email }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 8px 0;">
                                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Password:</span><br>
                                                                    <span style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $order->subscription_password ?? strtoupper(substr(md5($order->order_number), 0, 8)) }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif
                                        
                                        @if($order->pricingPlan && $order->pricingPlan->features)
                                        <!-- Features -->
                                        <div style="margin-top: 20px;">
                                            <p style="margin: 0 0 12px 0; font-size: 15px; color: #4a5568; font-weight: 600;">Plan Features:</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #4a5568; font-size: 14px; line-height: 1.8;">
                                                @foreach($order->pricingPlan->features as $feature)
                                                <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                        
                                    </td>
                                </tr>
                            </table>
                            @endforeach
                            
                            
                            <!-- Setup Instructions -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fff7ed; border: 2px solid #fed7aa; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 16px; color: #c2410c; font-weight: bold;">Important Setup Instructions</p>
                                        <ol style="margin: 0; padding-left: 20px; color: #7c2d12; font-size: 14px; line-height: 1.8;">
                                            <li><strong>For Smart TV/Android Box:</strong> Download our IPTV app from the app store or use any compatible IPTV player</li>
                                            <li><strong>For Mobile/Tablet:</strong> Download our mobile app or use IPTV Smarters, GSE Smart IPTV, or similar apps</li>
                                            <li><strong>For PC/Mac:</strong> Use VLC Media Player or any IPTV software that supports M3U playlists</li>
                                            <li><strong>Multi-Device Setup:</strong> Each device has its own unique credentials. Use the appropriate device credentials for each of your devices</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Quick Setup Guide -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">Quick Setup Guide</p>
                                        <ol style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                            <li>Download and install your preferred IPTV player application</li>
                                            <li>Open the app and select "Add Playlist" or "Login"</li>
                                            <li>Enter the server URL for the specific device (as shown in the credentials above)</li>
                                            <li>Enter the username and password for that specific device</li>
                                            <li>Save the settings and enjoy your IPTV service</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 18px; color: #334155; font-weight: bold;">Need Help?</p>
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #475569;">If you need assistance with setup or have any questions:</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                                            <li>Contact our technical support team</li>
                                            <li>Check our setup guides and tutorials</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Security Notes -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fff7ed; border: 2px solid #fed7aa; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 16px; color: #c2410c; font-weight: bold;">Important Security Notes</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #7c2d12; font-size: 14px; line-height: 1.8;">
                                            <li>Keep your credentials secure and do not share them with others</li>
                                            <li>Use only authorized IPTV applications</li>
                                            <li>Contact support immediately if you suspect unauthorized access</li>
                                            <li>Your service is for personal use only as per our terms of service</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 15px; color: #4a5568; line-height: 1.6;">Thank you for choosing {{ $company_name ?? config('app.name') }}. We hope you enjoy your premium IPTV experience!</p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #4c51bf;">The {{ $team_name ?? ($company_name ?? config('app.name')) . ' Team' }}</strong>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">This email contains sensitive account information. Please keep it secure.</p>
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ $company_name ?? config('app.name') }}. All rights reserved.</p>
                            @if(isset($website) && $website)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;"><a href="{{ $website }}" style="color: #cbd5e1;">{{ $website }}</a></p>
                            @endif
                            @if(isset($contact_email) && $contact_email)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">Contact: <a href="mailto:{{ $contact_email }}" style="color: #cbd5e1;">{{ $contact_email }}</a></p>
                            @endif
                            @if(isset($phone_number) && $phone_number)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">Phone: {{ $phone_number }}</p>
                            @endif
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>