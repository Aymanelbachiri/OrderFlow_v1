<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Reward Confirmation</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #10b981; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                Congratulations!</h1>
                            <p style="margin: 10px 0 0 0; color: #d1fae5; font-size: 16px;">Referral Reward Confirmed
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">

                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1a202c; font-weight: 600;">Hello
                                {{ $affiliate->email }},</p>

                            <p style="margin: 0 0 30px 0; font-size: 15px; color: #4a5568; line-height: 1.6;">
                                We are pleased to confirm that you have earned a <strong>referral reward</strong>
                                through our affiliate program!
                                Thank you for your continued partnership and for helping us grow our community.
                            </p>

                            <!-- Reward Confirmation Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin: 25px 0; background-color: #f0fdf4; border-left: 4px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3
                                            style="margin: 0 0 20px 0; color: #10b981; font-size: 20px; font-weight: bold;">
                                             Referral Reward Confirmed
                                        </h3>

                                        <!-- Stats -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%" style="margin-bottom: 20px;">
                                            <tr>
                                                <td
                                                    style="padding: 15px; background-color: #ffffff; border-radius: 6px; text-align: center; width: 50%;">
                                                    <div
                                                        style="font-size: 28px; font-weight: bold; color: #10b981; margin-bottom: 5px;">
                                                        {{ $affiliate->total_referrals }}</div>
                                                    <div
                                                        style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Total Referrals</div>
                                                </td>
                                                <td style="width: 20px;"></td>
                                                <td
                                                    style="padding: 15px; background-color: #ffffff; border-radius: 6px; text-align: center; width: 50%;">
                                                    <div
                                                        style="font-size: 28px; font-weight: bold; color: #10b981; margin-bottom: 5px;">
                                                        {{ $affiliate->total_rewards_earned }}</div>
                                                    <div
                                                        style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Months Earned</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if ($linkedDevice)
                                <!-- Device Reward Information -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="margin: 25px 0; background-color: #f7fafc; border-left: 4px solid #4c51bf; border-radius: 6px;">
                                    <tr>
                                        <td style="padding: 25px;">
                                            <h3
                                                style="margin: 0 0 20px 0; color: #4c51bf; font-size: 20px; font-weight: bold;">
                                                 Your Linked Device Reward
                                            </h3>

                                            <div
                                                style="background-color: #eef2ff; padding: 20px; border-radius: 6px; border: 1px solid #c7d2fe; margin-bottom: 20px;">
                                                <p
                                                    style="margin: 0 0 15px 0; font-size: 14px; color: #4c51bf; font-weight: bold; text-transform: uppercase;">
                                                    🔗 Rewarded Device {{ $linkedDevice['number'] }} Information
                                                </p>

                                                <table role="presentation" cellspacing="0" cellpadding="0"
                                                    border="0" width="100%">
                                                    @if ($order->subscription_username)
                                                        <tr>
                                                            <td style="padding: 8px 0;">
                                                                <span
                                                                    style="font-size: 13px; color: #6366f1; font-weight: 600; font-family: 'Courier New', monospace;">Username:</span><br>
                                                                <span
                                                                    style="font-size: 13px; color: #1e293b; font-family: 'Courier New', monospace; background-color: #ffffff; padding: 6px 10px; display: inline-block; border-radius: 4px; margin-top: 4px; word-break: break-all;">{{ $order->subscription_username }}</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>

                                                <div
                                                    style="margin-top: 15px; padding: 15px; background-color: #d1fae5; border-radius: 6px; border-left: 4px solid #10b981;">
                                                    <p
                                                        style="margin: 0; font-size: 14px; color: #065f46; font-weight: 600; text-align: center;">
                                                         This device has been credited with 1 additional month!
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <!-- Referral Code Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin: 25px 0; background-color: #fef3c7; border: 2px solid #fbbf24; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p
                                            style="margin: 0 0 12px 0; font-size: 16px; color: #d97706; font-weight: bold;">
                                            Keep Earning More Rewards!
                                        </p>
                                        <p
                                            style="margin: 0 0 15px 0; font-size: 14px; color: #92400e; line-height: 1.6;">
                                            Continue sharing your referral code to earn additional free months:
                                        </p>
                                        <div style="text-align: center; margin: 15px 0;">
                                            <span
                                                style="display: inline-block; background-color: #ffffff; padding: 12px 20px; border-radius: 6px; font-family: 'Courier New', monospace; font-weight: bold; color: #1f2937; font-size: 18px; border: 2px solid #f59e0b; letter-spacing: 1px;">
                                                {{ $affiliate->referral_code }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- How It Works -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin: 25px 0; background-color: #f0f9ff; border: 2px solid #bae6fd; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p
                                            style="margin: 0 0 15px 0; font-size: 18px; color: #0369a1; font-weight: bold;">
                                            How to Maximize Your Earnings
                                        </p>
                                        <ol
                                            style="margin: 0; padding-left: 20px; color: #075985; font-size: 14px; line-height: 1.8;">
                                            <li>Share your referral code with friends and family</li>
                                            <li>New customers use your code during checkout</li>
                                            <li>When their order is activated, you earn 1 free month</li>
                                            <li>Your subscription gets automatically extended</li>
                                            <li>Repeat to earn unlimited free months!</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>

                            <p
                                style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 15px; color: #4a5568; line-height: 1.6;">
                                Thank you for your valued partnership and continued support of our affiliate program. We
                                appreciate your efforts in helping us grow our community!
                            </p>

                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #718096; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #10b981;">The Affiliate Program Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">
                                This is an automated notification from our affiliate rewards system.
                            </p>
                            <p style="margin: 0 0 15px 0; font-size: 13px; color: #cbd5e1;">
                                &copy; {{ date('Y') }} {{ $source->company_name ?? config('app.name') }}. All rights
                                reserved.
                            </p>
                            @if (isset($source) && $source)
                                @if ($source->website)
                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">
                                        <a href="{{ $source->website }}"
                                            style="color: #cbd5e1;">{{ $source->website }}</a>
                                    </p>
                                @endif
                                @if ($source->contact_email)
                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">
                                        Contact: <a href="mailto:{{ $source->contact_email }}"
                                            style="color: #cbd5e1;">{{ $source->contact_email }}</a>
                                    </p>
                                @endif
                                @if ($source->phone_number)
                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">
                                        Phone: {{ $source->phone_number }}
                                    </p>
                                @endif
                            @endif
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
