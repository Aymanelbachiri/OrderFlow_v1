<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How Was Your Trial?</title>
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
                            <p style="margin: 10px 0 0 0; color: #e0e7ff; font-size: 16px;">Your Trial Has Ended</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello,</p>
                            
                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">We hope you enjoyed your trial experience with {{ $company_name }}! Your trial period has now ended, and we'd love to hear how it went.</p>
                            
                            <!-- Feedback Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 16px; color: #92400e; font-weight: bold;">💭 How was your experience?</p>
                                        <p style="margin: 0; font-size: 14px; color: #78350f; line-height: 1.6;">We value your feedback! If you enjoyed the trial, we'd love to have you as a full subscriber. If you had any issues, please let us know so we can improve.</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Benefits Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f0fdf4; border: 2px solid #86efac; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 15px 0; font-size: 16px; color: #166534; font-weight: bold;">✨ Why Subscribe?</p>
                                        <ul style="margin: 0; padding-left: 20px; color: #15803d; font-size: 14px; line-height: 1.8;">
                                            <li>Access to thousands of live channels</li>
                                            <li>HD & 4K quality streaming</li>
                                            <li>Movies & TV shows on demand</li>
                                            <li>Multi-device support</li>
                                            <li>24/7 customer support</li>
                                            <li>Regular content updates</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $subscribe_url }}" style="display: inline-block; background-color: #4c51bf; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold; padding: 15px 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(76, 81, 191, 0.3);">
                                            Subscribe Now
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Special Offer Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #eef2ff; border: 2px dashed #818cf8; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px; text-align: center;">
                                        <p style="margin: 0 0 10px 0; font-size: 18px; color: #4338ca; font-weight: bold;">🎁 Special Offer for Trial Users</p>
                                        <p style="margin: 0; font-size: 14px; color: #4f46e5; line-height: 1.6;">Subscribe within the next 48 hours and enjoy our best rates! Contact us for exclusive deals.</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #f8fafc; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 16px; color: #334155; font-weight: bold;">Questions?</p>
                                        <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">Our team is ready to help you choose the perfect plan for your needs. Don't hesitate to reach out!</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 15px; color: #4a5568; line-height: 1.6;">Thank you for trying {{ $company_name }}. We hope to see you as a subscriber soon!</p>
                            
                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #4c51bf;">{{ $team_name }}</strong>
                            </p>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ $company_name }}. All rights reserved.</p>
                            @if($website)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;"><a href="{{ $website }}" style="color: #93c5fd; text-decoration: none;">{{ $website }}</a></p>
                            @endif
                            @if($contact_email)
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">Contact: <a href="mailto:{{ $contact_email }}" style="color: #93c5fd; text-decoration: none;">{{ $contact_email }}</a></p>
                            @endif
                            <p style="margin: 15px 0 0 0; font-size: 11px; color: #94a3b8;">If you no longer wish to receive these emails, simply ignore this message.</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
