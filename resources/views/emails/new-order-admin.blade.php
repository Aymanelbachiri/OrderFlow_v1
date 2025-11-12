<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received - {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc2626; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">NEW ORDER ALERT</h1>
                            <p style="margin: 10px 0 0 0; color: #fecaca; font-size: 16px;">{{ config('app.name') }} Admin Panel</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Urgent Alert Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 25px 0; background-color: #fee2e2; border: 2px solid #fca5a5; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0; font-size: 15px; color: #991b1b; font-weight: bold; line-height: 1.6;">ACTION REQUIRED: A new IPTV subscription order has been received and requires activation!</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Order Information Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fef9c3; border-left: 4px solid #eab308; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #854d0e; font-size: 20px; font-weight: bold;">Order Information</h3>
                                        
                                        <!-- Order Info Grid -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Order Number</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->order_number }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Order Date</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->created_at->format('M d, Y H:i') }}</span>
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
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Service Plan</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->pricingPlan->display_name }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Amount</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">${{ number_format($order->amount, 2) }}</span>
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
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Payment Method</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Transaction ID</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->payment_id ?: 'N/A' }}</span>
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
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Status</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ ucfirst($order->status) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Duration</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->pricingPlan->duration_months }} month(s)</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if($order->order_type === 'subscription')
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Subscription Type</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->subscription_type_display }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Device Count</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->pricingPlan->device_count }} device(s)</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Device Count</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->pricingPlan->device_count }} device(s)</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Expires</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->expires_at ? $order->expires_at->format('M d, Y') : 'TBD' }}</span>
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
                            
                            <!-- Customer Information Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #1e40af; font-size: 20px; font-weight: bold;">Customer Information</h3>
                                        
                                        <!-- Customer Info Grid -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Customer Name</span>
                                                                <span style="font-size: 15px; color: #0f172a; font-weight: 600;">{{ $order->user->name }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Email Address</span>
                                                                <span style="font-size: 15px; color: #0f172a; font-weight: 600;">{{ $order->user->email }}</span>
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
                                                                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Customer Since</span>
                                                                <span style="font-size: 15px; color: #0f172a; font-weight: 600;">{{ $order->user->created_at->format('M d, Y') }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Total Orders</span>
                                                                <span style="font-size: 15px; color: #0f172a; font-weight: 600;">{{ $order->user->orders->count() }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Buttons -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 35px 0;">
                                <tr>
                                    <td align="center">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding: 0 10px;">
                                                    <a href="{{ route('admin.orders.show', $order) }}" style="display: inline-block; padding: 16px 32px; background-color: #16a34a; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">View Order Details</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        
                            
                            <!-- Reference IDs -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #eef2ff; border: 2px solid #c7d2fe; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; font-size: 14px; color: #4c51bf; line-height: 1.6;"><strong>Order ID:</strong> {{ $order->id }} | <strong>Customer ID:</strong> {{ $order->user->id }}</p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 14px; color: #cbd5e1; font-weight: 600;">{{ config('app.name') }} Admin Notification System</p>
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1;">This email was sent automatically when a new order was created.</p>
                            <p style="margin: 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>