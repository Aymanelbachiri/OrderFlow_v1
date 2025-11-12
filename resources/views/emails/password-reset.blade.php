<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
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
        .button:hover {
            background-color: #0056b3;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>Password Reset Request</h2>
    </div>

    <div class="content">
        <p>Hello {{ $user->name }},</p>

        <p>We received a request to reset your password for your {{ config('app.name') }} account. If you made this request, please click the button below to reset your password:</p>

        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        </div>

        <p>If the button above doesn't work, you can copy and paste the following link into your browser:</p>
        <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
            {{ $resetUrl }}
        </p>

        <div class="warning">
            <strong>Important Security Information:</strong>
            <ul>
                <li>This password reset link will expire in 60 minutes for security reasons.</li>
                <li>If you did not request a password reset, please ignore this email and your password will remain unchanged.</li>
                <li>For your security, never share this link with anyone.</li>
            </ul>
        </div>

        <p>If you're having trouble with your account or didn't request this password reset, please contact our support team immediately.</p>

        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $user->email }} because a password reset was requested for your account.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        
       
    </div>
</body>
</html>
