<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\PricingPlan;
use App\Mail\ResellerCredentialsMail;
use App\Events\ResellerOrderActivated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use AdminScopesData;

    /**
     * Send mail with admin-specific SMTP configuration
     */
    private function sendMailWithAdminConfig(string $to, $mailable, ?int $adminId = null): void
    {
        if (!$adminId) {
            // No admin_id, use default
            Mail::to($to)->send($mailable);
            return;
        }

        $admin = User::find($adminId);
        if (!$admin || !$admin->isAdmin()) {
            // Fallback to default
            Mail::to($to)->send($mailable);
            return;
        }

        $config = $admin->getConfig();
        $smtpConfig = $config->smtp_config ?? null;

        if (!$smtpConfig || empty($smtpConfig)) {
            // Fallback to default
            Mail::to($to)->send($mailable);
            return;
        }

        try {
            // Temporarily override mail config for this admin
            $originalConfig = config('mail');
            
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp', [
                'transport' => $smtpConfig['mailer'] ?? 'smtp',
                'host' => $smtpConfig['host'] ?? config('mail.mailers.smtp.host'),
                'port' => $smtpConfig['port'] ?? config('mail.mailers.smtp.port', 587),
                'encryption' => $smtpConfig['encryption'] ?? config('mail.mailers.smtp.encryption', 'tls'),
                'username' => $smtpConfig['username'] ?? config('mail.mailers.smtp.username'),
                'password' => $smtpConfig['password'] ?? config('mail.mailers.smtp.password'),
                'timeout' => config('mail.mailers.smtp.timeout', 60),
            ]);

            \Illuminate\Support\Facades\Config::set('mail.from', [
                'address' => $smtpConfig['from_address'] ?? config('mail.from.address'),
                'name' => $smtpConfig['from_name'] ?? config('mail.from.name'),
            ]);

            // Send email
            Mail::to($to)->send($mailable);

            // Restore original config
            \Illuminate\Support\Facades\Config::set('mail', $originalConfig);
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$to} using admin SMTP config: " . $e->getMessage());
            // Fallback to default
            Mail::to($to)->send($mailable);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'pricingPlan', 'resellerCreditPack', 'payments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter expiring soon (<= 7 days from now)
        if ($request->filter === 'expiring') {
            $query->whereNotNull('expires_at')
                  ->where('expires_at', '>=', now())
                  ->where('expires_at', '<=', now()->addDays(7));
        }

        // Filter expired orders (past date)
        if ($request->filter === 'expired') {
            $query->whereNotNull('expires_at')
                  ->where('expires_at', '<', now());
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by plan type
        if ($request->filled('plan_type')) {
            if ($request->plan_type === 'regular') {
                // Regular orders: have pricing_plan_id but not reseller_credit_pack_id
                $query->whereNotNull('pricing_plan_id')
                      ->whereNull('reseller_credit_pack_id');
            } elseif ($request->plan_type === 'reseller') {
                // Reseller orders: either have reseller pricing plans OR credit packs
                $query->where(function($q) {
                    $q->whereHas('pricingPlan', function($planQuery) {
                        $planQuery->where('plan_type', 'reseller');
                    })->orWhereNotNull('reseller_credit_pack_id');
                });
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Scope to admin's data (unless super admin)
        $user = auth()->user();
        if ($user && $user->isAdmin() && !$user->isSuperAdmin()) {
            $query->where('admin_id', $user->id);
        }

        $orders = $query->latest()->paginate(20);

        // Get statistics for dashboard (scoped to admin)
        $statsQuery = Order::query();
        if ($user && $user->isAdmin() && !$user->isSuperAdmin()) {
            $statsQuery->where('admin_id', $user->id);
        }
        
        $totalOrdersCount = $statsQuery->count();
        $activeOrdersCount = (clone $statsQuery)->where('status', 'active')->count();
        $pendingOrdersCount = (clone $statsQuery)->where('status', 'pending')->count();
        $expiredOrdersCount = (clone $statsQuery)->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();
        $expiringSoonCount = (clone $statsQuery)->whereNotNull('expires_at')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->count();
        $resellerOrdersCount = (clone $statsQuery)->where(function($q) {
            $q->whereHas('pricingPlan', function($planQuery) {
                $planQuery->where('plan_type', 'reseller');
            })->orWhereNotNull('reseller_credit_pack_id');
        })->count();

        // Calculate total revenue (scoped to admin)
        $totalRevenue = (clone $statsQuery)->where('status', '!=', 'cancelled')->sum('amount');

        return view('admin.orders.index', compact(
            'orders',
            'totalOrdersCount',
            'activeOrdersCount',
            'pendingOrdersCount',
            'resellerOrdersCount',
            'expiredOrdersCount',
            'expiringSoonCount',
            'totalRevenue'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereIn('role', ['client', 'reseller'])->orderBy('name')->get();
        $pricingPlans = PricingPlan::where('is_active', true)->orderBy('display_name')->get();

        return view('admin.orders.create', compact('users', 'pricingPlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
            'payment_method' => 'required|in:email_link,stripe,paypal,crypto,manual',
            'status' => 'required|in:pending,active,expired,cancelled',
            'amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'admin_notes' => 'nullable|string|max:1000',
            'send_order_confirmation' => 'boolean',
            'send_payment_instructions' => 'boolean',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $pricingPlan = PricingPlan::findOrFail($validated['pricing_plan_id']);

        // Use custom amount or plan price
        $amount = $validated['amount'] ?? $pricingPlan->price;

        // Calculate expiry date if not provided
        $expiresAt = $validated['expires_at'] ?? null;
        if (!$expiresAt && $validated['starts_at']) {
            $startsAt = \Carbon\Carbon::parse($validated['starts_at']);
            $expiresAt = $startsAt->copy()->addMonths($pricingPlan->duration_months);
        } elseif (!$expiresAt) {
            $expiresAt = now()->addMonths($pricingPlan->duration_months);
        }

        // Generate unique order number
        $orderNumber = 'ORD-' . strtoupper(uniqid());

        $user = auth()->user();
        $adminId = ($user && $user->isAdmin() && !$user->isSuperAdmin()) ? $user->id : null;

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $validated['user_id'],
            'admin_id' => $adminId,
            'pricing_plan_id' => $validated['pricing_plan_id'],
            'payment_method' => $validated['payment_method'],
            'status' => $validated['status'],
            'amount' => $amount,
            'starts_at' => $validated['starts_at'] ?? now(),
            'expires_at' => $expiresAt,
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Dispatch OrderCreated event to send admin notification emails
        \App\Events\OrderCreated::dispatch($order);

        // Send emails if requested
        if ($request->boolean('send_order_confirmation')) {
            // Send order confirmation email
            Mail::to($user->email)->send(new \App\Mail\OrderConfirmationMail($order));
        }

        if ($request->boolean('send_payment_instructions') && $validated['status'] === 'pending') {
            // Send payment instructions email
            Mail::to($user->email)->send(new \App\Mail\PaymentInstructionsMail($order));
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'pricingPlan', 'resellerCreditPack', 'payments', 'renewalNotifications']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,active,expired,cancelled',
            'amount' => 'required|numeric|min:0',
            'pricing_plan_id' => 'nullable|exists:pricing_plans,id',
            'reseller_credit_pack_id' => 'nullable|exists:reseller_credit_packs,id',
            'subscription_type' => 'nullable|in:new,renewal',
            'payment_method' => 'required|in:email_link,stripe,paypal,crypto,manual',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'admin_notes' => 'nullable|string|max:1000',
            'subscription_username' => 'nullable|string|max:255',
            'subscription_password' => 'nullable|string|max:255',
            'subscription_url' => 'nullable|url|max:255',
            'devices' => 'nullable|array',
            'devices.*.username' => 'nullable|string|max:255',
            'devices.*.password' => 'nullable|string|max:255',
            'devices.*.url' => 'nullable|url|max:255',
            'reseller_username' => 'nullable|string|max:255',
            'reseller_password' => 'nullable|string|max:255',
            'reseller_login_url' => 'nullable|url|max:255',
        ]);

        // Map admin_notes to notes field
        if (isset($validated['admin_notes'])) {
            $validated['notes'] = $validated['admin_notes'];
            unset($validated['admin_notes']);
        }

        // Handle pricing plan change
        if (isset($validated['pricing_plan_id']) && $validated['pricing_plan_id'] != $order->pricing_plan_id) {
            $newPlan = \App\Models\PricingPlan::find($validated['pricing_plan_id']);
            
            // Update amount to match new plan price
            if ($newPlan) {
                $validated['amount'] = $newPlan->price;
            }
            
            // Log the plan change
            Log::info('Order pricing plan changed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_plan_id' => $order->pricing_plan_id,
                'new_plan_id' => $validated['pricing_plan_id'],
                'old_amount' => $order->amount,
                'new_amount' => $validated['amount'],
                'changed_by' => auth()->user()->email,
            ]);
        }

        // Handle credit pack change
        if (isset($validated['reseller_credit_pack_id']) && $validated['reseller_credit_pack_id'] != $order->pricing_plan_id) {
            $newCreditPack = \App\Models\ResellerCreditPack::find($validated['reseller_credit_pack_id']);
            
            // Update amount to match new credit pack price
            if ($newCreditPack) {
                $validated['amount'] = $newCreditPack->price;
                $validated['pricing_plan_id'] = $newCreditPack->id; // Map to pricing_plan_id for database
            }
            
            // Log the credit pack change
            Log::info('Order credit pack changed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_credit_pack_id' => $order->pricing_plan_id,
                'new_credit_pack_id' => $validated['reseller_credit_pack_id'],
                'old_amount' => $order->amount,
                'new_amount' => $validated['amount'],
                'changed_by' => auth()->user()->email,
            ]);
        }

        // Process multi-device data if present
        if (isset($validated['devices']) && is_array($validated['devices'])) {
            $devices = [];
            foreach ($validated['devices'] as $deviceNumber => $deviceData) {
                if (!empty($deviceData['username']) || !empty($deviceData['password']) || !empty($deviceData['url'])) {
                    $devices[] = [
                        'device_number' => $deviceNumber,
                        'username' => $deviceData['username'] ?? '',
                        'password' => $deviceData['password'] ?? '',
                        'url' => $deviceData['url'] ?? '',
                    ];
                }
            }
            $validated['devices'] = $devices;

            // Update main subscription fields with first device for backward compatibility
            if (!empty($devices)) {
                $firstDevice = $devices[0];
                $validated['subscription_username'] = $firstDevice['username'];
                $validated['subscription_password'] = $firstDevice['password'];
                $validated['subscription_url'] = $firstDevice['url'];
            }
        }

        $order->update($validated);

        // Send emails if requested
        if ($request->boolean('send_status_update')) {
            $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\OrderStatusUpdateMail($order), $order->admin_id);
        }

        // If credentials are provided and not sent yet, send them
        if ($request->boolean('send_credentials') &&
            ($order->subscription_username || $order->reseller_username || $order->user->reseller_panel_url) &&
            $order->status === 'active') {
            $this->sendCredentials($order);
        }

        // Auto-send credentials for reseller orders when activated
        if ($order->status === 'active' &&
            ($order->pricingPlan->plan_type === 'reseller' || $order->user->role === 'reseller') &&
            !$order->credentials_sent &&
            $order->user->reseller_panel_url) {
            $this->sendCredentials($order);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Send credentials to customer
     */
    public function sendCredentials(Order $order)
    {
        $adminId = $order->admin_id;

        if ($order->user->role === 'reseller' || $order->pricingPlan->plan_type === 'reseller') {
            // Use the new ResellerCredentialsMail for reseller orders (with admin SMTP if available)
            $this->sendMailWithAdminConfig($order->user->email, new ResellerCredentialsMail($order, $order->user), $adminId);

            // Trigger the reseller order activated event
            ResellerOrderActivated::dispatch($order);
        } else {
            // Send credentials email for regular orders
            $processedOrders = collect();

            if ($order->devices && is_array($order->devices) && count($order->devices) > 0) {
                foreach ($order->devices as $device) {
                    $processedOrders->push((object)[
                        'subscription_username' => $device['username'] ?? null,
                        'subscription_password' => $device['password'] ?? null,
                        'subscription_url' => $device['url'] ?? null,
                    ]);
                }
            } else {
                $processedOrders->push((object)[
                    'subscription_username' => $order->subscription_username,
                    'subscription_password' => $order->subscription_password,
                    'subscription_url' => $order->subscription_url,
                ]);
            }

            $this->sendMailWithAdminConfig($order->user->email, new ClientCredentialsMail($order->user, $processedOrders), $adminId);
        }

        $order->update([
            'credentials_sent' => true,
            'credentials_sent_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Credentials sent successfully.');
    }

    /**
     * Send IPTV panel credentials for credit pack orders
     */
    public function sendCreditPackCredentials(Order $order)
    {
        // Send credentials email for credit pack orders using Mailable
        $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\CreditPackCredentialsMail($order, $order->user), $order->admin_id);

        $order->update([
            'credentials_sent' => true,
            'credentials_sent_at' => now(),
        ]);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'pricingPlan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Number', 'Customer', 'Email', 'Plan', 'Amount', 'Status', 'Created', 'Expires']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->pricingPlan->display_name,
                    $order->amount,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->expires_at ? $order->expires_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Activate an order with credentials
     */
    public function activate(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending orders can be activated.');
        }

        // Check if this is a credit pack order
        if ($order->order_type === 'credit_pack') {
            // Validate IPTV panel credentials for credit pack
            $validated = $request->validate([
                'iptv_panel_url' => 'required|url|max:255',
                'iptv_panel_username' => 'required|string|max:255',
                'iptv_panel_password' => 'required|string|max:255',
                'send_credentials_email' => 'boolean',
            ]);

            // For credit pack orders, mark as active and set reseller credentials
            $order->update([
                'status' => 'active',
                'completed_at' => now(),
                'reseller_username' => $validated['iptv_panel_username'],
                'reseller_password' => $validated['iptv_panel_password'],
                'reseller_login_url' => $validated['iptv_panel_url'],
                'credentials_sent' => false, // Will be set to true after email is sent
            ]);

            // Send credentials email if requested
            if ($request->boolean('send_credentials_email', true)) {
                try {
                    // Send email with IPTV panel credentials for credit pack
                    $this->sendCreditPackCredentials($order);

                    $order->update([
                        'credentials_sent' => true,
                        'credentials_sent_at' => now()
                    ]);

                    return redirect()->back()
                        ->with('success', 'Credit pack order activated successfully! IPTV panel credentials have been sent to the reseller via email.');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('warning', 'Credit pack order activated successfully, but there was an issue sending the credentials email. Please send them manually.');
                }
            }

            return redirect()->back()
                ->with('success', 'Credit pack order activated successfully. You can send the IPTV panel credentials manually if needed.');
        }

        // Check if this is a reseller subscription order (based strictly on plan type)
        $isResellerOrder = $order->pricingPlan && $order->pricingPlan->plan_type === 'reseller';

        if ($isResellerOrder) {
            // Validate reseller credentials
            $validated = $request->validate([
                'reseller_panel_url' => 'required|url|max:255',
                'reseller_username' => 'required|string|max:255',
                'reseller_password' => 'required|string|max:255',
                'send_credentials_email' => 'boolean',
            ]);

            // Update order with reseller credentials and activate
            $order->update([
                'status' => 'active',
                'starts_at' => $order->starts_at ?? now(),
                'reseller_username' => $validated['reseller_username'],
                'reseller_password' => $validated['reseller_password'],
                'reseller_login_url' => $validated['reseller_panel_url'],
                'credentials_sent' => false, // Will be set to true after email is sent
            ]);

            // Send credentials email if requested
            if ($request->boolean('send_credentials_email', true)) {
                try {
                    // Use the reseller credentials mail
                    $this->sendMailWithAdminConfig($order->user->email, new ResellerCredentialsMail($order, $order->user), $order->admin_id);

                    // Trigger the reseller order activated event
                    ResellerOrderActivated::dispatch($order);

                    $order->update([
                        'credentials_sent' => true,
                        'credentials_sent_at' => now()
                    ]);

                    return redirect()->back()
                        ->with('success', 'Reseller order activated successfully! Reseller panel credentials have been sent to the customer via email.');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('warning', 'Reseller order activated successfully, but there was an issue sending the credentials email. Please send them manually.');
                }
            }

            return redirect()->back()
                ->with('success', 'Reseller order activated successfully. You can send the reseller panel credentials manually if needed.');
        } else {
            // Handle regular orders with device credentials
            $deviceCount = $order->pricingPlan->device_count;

            // Validate device credentials
            $validated = $request->validate([
                'devices' => 'required|array|size:' . $deviceCount,
                'devices.*.username' => 'required|string|max:255',
                'devices.*.password' => 'required|string|max:255',
                'devices.*.url' => 'required|url|max:255',
                'send_credentials_email' => 'boolean',
            ]);

            // Prepare devices data
            $devices = [];
            foreach ($validated['devices'] as $deviceIndex => $deviceData) {
                $devices[] = [
                    'device_number' => $deviceIndex,
                    'username' => $deviceData['username'],
                    'password' => $deviceData['password'],
                    'url' => $deviceData['url'],
                ];
            }

            // For backward compatibility, set the first device as main subscription
            $firstDevice = $devices[0] ?? null;

            // Update order with credentials and activate
            $order->update([
                'status' => 'active',
                'starts_at' => $order->starts_at ?? now(),
                'subscription_username' => $firstDevice['username'] ?? null,
                'subscription_password' => $firstDevice['password'] ?? null,
                'subscription_url' => $firstDevice['url'] ?? null,
                'devices' => $devices,
                'credentials_sent' => false, // Will be set to true after email is sent
            ]);

            // Send credentials email if requested
            if ($request->boolean('send_credentials_email', true)) {
                try {
                    // Check if this is a renewal order
                    if ($order->subscription_type === 'renewal') {
                        // Find the original order if available
                        $originalOrder = null;
                        if ($order->payment_id) {
                            $paymentIntent = \App\Models\PaymentIntent::where('payment_intent_id', $order->payment_id)->first();
                            if ($paymentIntent && isset($paymentIntent->order_data['renewal_of_order_id'])) {
                                $originalOrder = Order::find($paymentIntent->order_data['renewal_of_order_id']);
                            }
                        }
                        
                        // Send renewal email
                        $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\AccountRenewedMail($order, $originalOrder), $order->admin_id);
                    } else {
                        // Send regular credentials email for new orders
                        $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\ClientCredentialsMail($order->user, collect([$order])), $order->admin_id);
                    }

                    $order->update([
                        'credentials_sent' => true,
                        'credentials_sent_at' => now(),
                    ]);

                    $successMessage = $order->subscription_type === 'renewal' 
                        ? 'Renewal activated successfully! Account renewed email has been sent to the customer.'
                        : 'Order activated successfully! Multi-device IPTV credentials have been sent to the customer via email.';

                    return redirect()->back()
                        ->with('success', $successMessage);
                } catch (\Exception $e) {
                    Log::error('Failed to send credentials email', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                    return redirect()->back()
                        ->with('warning', 'Order activated successfully, but there was an issue sending the credentials email. Please send them manually.');
                }
            }

            return redirect()->back()
                ->with('success', 'Order activated successfully. You can send the multi-device credentials manually if needed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Log the deletion for audit purposes
        Log::info('Order deleted by admin', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'customer_email' => $order->user->email,
            'amount' => $order->amount,
            'deleted_by' => auth()->user()->email,
        ]);

        // Delete associated payments first (if any)
        $order->payments()->delete();

        // Delete the order
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
