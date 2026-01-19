<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Trial Credentials</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4c51bf; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">{{ $company_name }}</h1>
                            <p style="margin: 10px 0 0 0; color: #e0e7ff; font-size: 16px;">🎉 Your Trial Has Been Approved!</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello,</p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">Great news! Your trial request has been approved. Below you'll find your trial credentials to get started.</p>
                            
                            <!-- Trial Info Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #166534; font-weight: bold; text-transform: uppercase;">Trial Information</p>
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span style="font-size: 13px; color: #166534;">Duration:</span>
                                                    <span style="font-size: 13px; color: #15803d; font-weight: 600;">{{ $trial_duration }}</span>
                                                </td>
                                            </tr>
                                            @if($server_type)
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span style="font-size: 13px; color: #166534;">Server:</span>
                                                    <span style="font-size: 13px; color: #15803d; font-weight: 600;">{{ $server_type }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($expires_at)
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span style="font-size: 13px; color: #166534;">Expires:</span>
                                                    <span style="font-size: 13px; color: #15803d; font-weight: 600;">{{ $expires_at }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Credentials Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #eef2ff; border-radius: 6px; border: 1px solid #c7d2fe;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #4c51bf; font-weight: bold; text-transform: uppercase;">🔐 Your Trial Credentials</p>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600;">Server URL:</span><br>
                                                    <span style="font-size: 14px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 8px 12px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all; border: 1px solid #e2e8f0;">{{ $trial_url }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600;">Username:</span><br>
                                                    <span style="font-size: 14px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 8px 12px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all; border: 1px solid #e2e8f0;">{{ $trial_username }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="font-size: 13px; color: #6366f1; font-weight: 600;">Password:</span><br>
                                                    <span style="font-size: 14px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 8px 12px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all; border: 1px solid #e2e8f0;">{{ $trial_password }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Quick Setup Guide -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 16px; color: #0369a1; font-weight: bold;">📱 Quick Setup Guide</p>
                                        <ol style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                            <li>Download your preferred IPTV player app</li>
                                            <li>Open the app and select "Add Playlist" or "Login"</li>
                                            <li>Enter the Server URL, Username, and Password above</li>
                                            <li>Save and enjoy your trial!</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 16px; color: #334155; font-weight: bold;">Need Help?</p>
                                        <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">If you have any questions or need assistance with setup, feel free to contact our support team. We're here to help!</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 15px; color: #4a5568; line-height: 1.6;">Enjoy your trial experience with {{ $company_name }}!</p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #4c51bf;">{{ $team_name }}</strong>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">This email contains your trial credentials. Please keep it secure.</p>
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ $company_name }}. All rights reserved.</p>
                            @if($website)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;"><a href="{{ $website }}" style="color: #93c5fd; text-decoration: none;">{{ $website }}</a></p>
                            @endif
                            @if($contact_email)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">Contact: <a href="mailto:{{ $contact_email }}" style="color: #93c5fd; text-decoration: none;">{{ $contact_email }}</a></p>
                            @endif
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
