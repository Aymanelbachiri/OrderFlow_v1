<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                Order Confirmation
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 14px; opacity: 0.9;">
                                Thank you for your purchase!
                            </p>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px;">
                            <p style="margin: 0 0 15px 0; color: #333333; font-size: 16px; line-height: 1.5;">
                                Dear <strong>{{ $customer->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 15px 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                We have successfully received your order for <strong>{{ $product->name }}</strong>. Your payment has been confirmed and we're processing your request.
                            </p>
                        </td>
                    </tr>

                    <!-- Order Details -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 6px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; color: #333333; font-size: 18px; font-weight: bold;">
                                            Order Details
                                        </h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0" border="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Order Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: bold; text-align: right; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $order->order_number }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Order Date:</td>
                                                <td style="color: #333333; font-size: 14px; text-align: right; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $order->created_at->format('M d, Y H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Product:</td>
                                                <td style="color: #333333; font-size: 14px; text-align: right; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $product->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Type:</td>
                                                <td style="color: #333333; font-size: 14px; text-align: right; border-bottom: 1px solid #e0e0e0;">
                                                    {{ ucfirst($product->product_type) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Payment Method:</td>
                                                <td style="color: #333333; font-size: 14px; text-align: right; border-bottom: 1px solid #e0e0e0;">
                                                    {{ ucfirst($order->payment_method) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding-top: 10px;">Amount Paid:</td>
                                                <td style="color: #667eea; font-size: 20px; font-weight: bold; text-align: right; padding-top: 10px;">
                                                    ${{ number_format($order->amount, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Product Description -->
                    @if($product->description)
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; border-radius: 4px;">
                                <h3 style="margin: 0 0 10px 0; color: #333333; font-size: 16px; font-weight: bold;">
                                    About This Product
                                </h3>
                                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                    {{ $product->description }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    <!-- Next Steps -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <h3 style="margin: 0 0 15px 0; color: #333333; font-size: 18px; font-weight: bold;">
                                What Happens Next?
                            </h3>
                            <ul style="margin: 0; padding-left: 20px; color: #666666; font-size: 14px; line-height: 1.8;">
                                <li>Our team will process your order shortly</li>
                                <li>You will receive further instructions via email within 24 hours</li>
                                <li>If you have any questions, feel free to contact our support team</li>
                            </ul>
                        </td>
                    </tr>

                    <!-- Customer Info -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 6px; padding: 20px;">
                                <tr>
                                    <td>
                                        <h3 style="margin: 0 0 10px 0; color: #333333; font-size: 16px; font-weight: bold;">
                                            Your Contact Information
                                        </h3>
                                        <p style="margin: 5px 0; color: #666666; font-size: 14px;">
                                            <strong>Email:</strong> {{ $customer->email }}
                                        </p>
                                        @if($customer->phone)
                                        <p style="margin: 5px 0; color: #666666; font-size: 14px;">
                                            <strong>Phone:</strong> {{ $customer->phone }}
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Support -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; text-align: center;">
                            <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">
                                Need help? Contact our support team
                            </p>
                            @if(isset($contact_email) && $contact_email)
                            <p style="margin: 0; color: #667eea; font-size: 14px; font-weight: bold;">
                                <a href="mailto:{{ $contact_email }}" style="color: #667eea; text-decoration: none;">{{ $contact_email }}</a>
                            </p>
                            @elseif(isset($phone_number) && $phone_number)
                            <p style="margin: 0; color: #667eea; font-size: 14px; font-weight: bold;">
                                {{ $phone_number }}
                            </p>
                            @elseif(isset($website) && $website)
                            <p style="margin: 0; color: #667eea; font-size: 14px; font-weight: bold;">
                                <a href="{{ $website }}" style="color: #667eea; text-decoration: none;">{{ $website }}</a>
                            </p>
                            @else
                            <p style="margin: 0; color: #667eea; font-size: 14px; font-weight: bold;">
                                {{ config('mail.from.address') }}
                            </p>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; text-align: center; color: #999999; font-size: 12px; line-height: 1.5;">
                            <p style="margin: 0 0 5px 0;">
                                © {{ date('Y') }} {{ $company_name ?? config('app.name') }}. All rights reserved.
                            </p>
                            @if(isset($website) && $website)
                            <p style="margin: 5px 0;">
                                <a href="{{ $website }}" style="color: #999999; text-decoration: none;">{{ $website }}</a>
                            </p>
                            @endif
                            @if(isset($contact_email) && $contact_email)
                            <p style="margin: 5px 0;">
                                Contact: <a href="mailto:{{ $contact_email }}" style="color: #999999; text-decoration: none;">{{ $contact_email }}</a>
                            </p>
                            @endif
                            @if(isset($phone_number) && $phone_number)
                            <p style="margin: 5px 0;">
                                Phone: {{ $phone_number }}
                            </p>
                            @endif
                            <p style="margin: 5px 0 0 0;">
                                This is an automated email. Please do not reply to this message.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

