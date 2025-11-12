<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reseller Order - {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc2626; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">NEW RESELLER ORDER ALERT</h1>
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
                                        <p style="margin: 0; font-size: 15px; color: #991b1b; font-weight: bold; line-height: 1.6;">ACTION REQUIRED: A new reseller credit pack order has been received. Panel account setup needed!</p>
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
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Order ID</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $order->id }}</span>
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
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Status</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ ucfirst($order->status) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Customer Details Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #1e40af; font-size: 20px; font-weight: bold;">Customer Details</h3>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Customer Name</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $customer->name }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Email</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $customer->email }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if($customer->phone)
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Phone</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $customer->phone }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Customer ID</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $customer->id }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($customer->reseller_panel_username)
                                            <tr>
                                                <td colspan="2" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Requested Panel Username</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $customer->reseller_panel_username }}</span>
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
                            
                            <!-- Credit Pack Details -->
                            @if($creditPack)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #d1fae5; border-left: 4px solid #10b981; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        
                                        <h3 style="margin: 0 0 20px 0; color: #047857; font-size: 20px; font-weight: bold;">Credit Pack Details</h3>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Pack Name</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $creditPack->name }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Credits Amount</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $creditPack->credits_amount }} Credits</span>
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
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Price</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">${{ number_format($creditPack->price, 2) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="50%" style="padding: 10px; vertical-align: top;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ffffff; border-radius: 6px; padding: 12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Price Per Credit</span>
                                                                <span style="font-size: 15px; color: #1c1917; font-weight: 600;">{{ $creditPack->formatted_price_per_credit }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        @if($creditPack->features && count($creditPack->features) > 0)
                                        <div style="margin-top: 15px; padding: 15px; background-color: #ffffff; border-radius: 6px;">
                                            <p style="margin: 0 0 10px 0; font-size: 14px; color: #047857; font-weight: bold;">Pack Features:</p>
                                            <ul style="margin: 0; padding-left: 20px; color: #065f46; font-size: 13px; line-height: 1.6;">
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
                            
                            <!-- Action Required -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 18px; color: #92400e; font-weight: bold;">Required Actions</p>
                                        <ol style="margin: 0; padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.8;">
                                            <li style="margin-bottom: 8px;"><strong>Create Panel Account:</strong> Set up IPTV panel account for the reseller</li>
                                            <li style="margin-bottom: 8px;"><strong>Set Credentials:</strong> Configure panel URL, username, and password</li>
                                            <li style="margin-bottom: 8px;"><strong>Allocate Credits:</strong> Assign {{ $creditPack->credits_amount ?? 'N/A' }} credits to the reseller account</li>
                                            <li><strong>Activate Order:</strong> Change order status to 'active' and send credentials email</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Quick Actions -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" style="display: inline-block; padding: 15px 30px; background-color: #dc2626; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">Manage This Order</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Footer Note -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; padding: 20px; background-color: #f1f5f9; border-radius: 6px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.6;">
                                            <strong>Note:</strong> The customer has been notified that their panel account will be created within 24 hours. Please process this order as soon as possible.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #cbd5e1; font-weight: 600;">Reseller Order #{{ $order->order_number }}</p>
                            <p style="margin: 0; font-size: 13px; color: #cbd5e1;">&copy; {{ date('Y') }} {{ config('app.name') }} Admin Panel</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

