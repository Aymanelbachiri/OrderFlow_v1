<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Custom Product Order</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                NEW CUSTOM PRODUCT ORDER
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 14px; opacity: 0.9;">
                                A customer has purchased a custom product
                            </p>
                        </td>
                    </tr>

                    <!-- Alert Box -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px;">
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 4px;">
                                <p style="margin: 0; color: #92400e; font-size: 14px; font-weight: bold;">
                                    ⚠️ Action Required: Please review and process this order
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Order Information -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 6px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; color: #333333; font-size: 18px; font-weight: bold;">
                                            Order Information
                                        </h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0" border="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0; width: 40%;">Order Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: bold; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $order->order_number }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Order Date:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $order->created_at->format('M d, Y H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Order Type:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                                        Custom Product
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Status:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Payment Method:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    {{ ucfirst($order->payment_method) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding-top: 10px;">Amount Paid:</td>
                                                <td style="color: #f59e0b; font-size: 20px; font-weight: bold; padding-top: 10px;">
                                                    ${{ number_format($order->amount, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Customer Details -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 6px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; color: #333333; font-size: 18px; font-weight: bold;">
                                            Customer Details
                                        </h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0" border="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0; width: 40%;">Name:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $customer->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Email:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    <a href="mailto:{{ $customer->email }}" style="color: #f59e0b; text-decoration: none;">
                                                        {{ $customer->email }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @if($customer->phone)
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Phone:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $customer->phone }}
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Customer Since:</td>
                                                <td style="color: #333333; font-size: 14px;">
                                                    {{ $customer->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Product Details -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 6px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; color: #333333; font-size: 18px; font-weight: bold;">
                                            Product Details
                                        </h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0" border="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0; width: 40%;">Product Name:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: bold; border-bottom: 1px solid #e0e0e0;">
                                                    {{ $product->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; border-bottom: 1px solid #e0e0e0;">Type:</td>
                                                <td style="color: #333333; font-size: 14px; border-bottom: 1px solid #e0e0e0;">
                                                    {{ ucfirst($product->product_type) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Price:</td>
                                                <td style="color: #333333; font-size: 14px;">
                                                    ${{ number_format($product->price, 2) }}
                                                </td>
                                            </tr>
                                        </table>

                                        @if($product->description)
                                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                                            <p style="margin: 0; color: #666666; font-size: 13px; line-height: 1.6;">
                                                <strong>Description:</strong><br>
                                                {{ $product->description }}
                                            </p>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Required Actions -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <div style="background-color: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px;">
                                <h3 style="margin: 0 0 10px 0; color: #1e40af; font-size: 16px; font-weight: bold;">
                                    Required Actions
                                </h3>
                                <ul style="margin: 0; padding-left: 20px; color: #1e3a8a; font-size: 14px; line-height: 1.8;">
                                    <li>Review order details and verify payment</li>
                                    <li>Contact customer if needed for clarification</li>
                                    <li>Process and fulfill the order</li>
                                    <li>Update order status in admin panel</li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px; text-align: center;">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                                View Order in Admin Panel
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; text-align: center; color: #999999; font-size: 12px; line-height: 1.5;">
                            <p style="margin: 0 0 5px 0;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <p style="margin: 0;">
                                This is an automated admin notification email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

