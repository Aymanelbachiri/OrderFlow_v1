<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\PricingPlan;
use App\Models\Source;
use App\Models\CustomProduct;
use App\Mail\ResellerCredentialsMail;
use App\Events\ResellerOrderActivated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
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
                  ->orWhere('subscription_username', 'like', "%{$search}%")
                  ->orWhere('subscription_password', 'like', "%{$search}%")
                  ->orWhere('subscription_url', 'like', "%{$search}%")
                  ->orWhere('reseller_username', 'like', "%{$search}%")
                  ->orWhere('reseller_password', 'like', "%{$search}%")
                  ->orWhere('reseller_login_url', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  // Search in devices JSON array (for multi-device credentials)
                  ->orWhereRaw("devices LIKE ?", ["%{$search}%"])
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        // Get statistics for dashboard
        $totalOrdersCount = Order::count();
        $activeOrdersCount = Order::where('status', 'active')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $expiredOrdersCount = Order::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();
        $expiringSoonCount = Order::whereNotNull('expires_at')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->count();
        $resellerOrdersCount = Order::where(function($q) {
            $q->whereHas('pricingPlan', function($planQuery) {
                $planQuery->where('plan_type', 'reseller');
            })->orWhereNotNull('reseller_credit_pack_id');
        })->count();

        // Calculate total revenue
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('amount');

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
        $resellerCreditPacks = \App\Models\ResellerCreditPack::where('is_active', true)->orderBy('name')->get();
        $customProducts = CustomProduct::where('is_active', true)->orderBy('name')->get();
        $sources = Source::orderBy('name')->get();

        return view('admin.orders.create', compact('users', 'pricingPlans', 'resellerCreditPacks', 'customProducts', 'sources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pricing_plan_id' => 'nullable|exists:pricing_plans,id',
            'reseller_credit_pack_id' => 'nullable|exists:reseller_credit_packs,id',
            'custom_product_id' => 'nullable|exists:custom_products,id',
            'source' => 'nullable|string|max:255',
            'payment_method' => 'required|in:email_link,stripe,paypal,crypto,manual',
            'status' => 'required|in:pending,active,expired,cancelled,completed',
            'amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'admin_notes' => 'nullable|string|max:1000',
            'subscription_username' => 'nullable|string|max:255',
            'subscription_password' => 'nullable|string|max:255',
            'subscription_url' => 'nullable|url|max:255',
            'devices' => 'nullable|array',
            'devices.*.username' => 'nullable|string|max:255',
            'devices.*.password' => 'nullable|string|max:255',
            'devices.*.url' => 'nullable|string|max:255',
            'reseller_username' => 'nullable|string|max:255',
            'reseller_password' => 'nullable|string|max:255',
            'reseller_login_url' => 'nullable|url|max:255',
            'send_order_confirmation' => 'boolean',
            'send_payment_instructions' => 'boolean',
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        // Determine order type: custom product, reseller credit pack, or regular pricing plan
        $isCustomProductOrder = !empty($validated['custom_product_id']);
        $isResellerOrder = !empty($validated['reseller_credit_pack_id']);
        
        // Validate that only one product type is selected
        $selectedTypes = array_filter([
            'custom_product' => $isCustomProductOrder,
            'reseller_credit_pack' => $isResellerOrder,
            'pricing_plan' => !empty($validated['pricing_plan_id']),
        ]);
        
        if (count($selectedTypes) > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['pricing_plan_id' => 'Please select only one: pricing plan, reseller credit pack, or custom product.']);
        }
        
        if ($isCustomProductOrder) {
            // Validate that other product types are not set
            if (!empty($validated['pricing_plan_id']) || !empty($validated['reseller_credit_pack_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['custom_product_id' => 'Cannot select custom product with pricing plan or credit pack.']);
            }
            
            $customProduct = CustomProduct::findOrFail($validated['custom_product_id']);
            $amount = $validated['amount'] ?? $customProduct->price;
            $expiresAt = $validated['expires_at'] ?? null; // Custom products may or may not expire
            $orderType = 'custom_product';
        } elseif ($isResellerOrder) {
            // Validate that pricing_plan_id is not set when reseller_credit_pack_id is set
            if (!empty($validated['pricing_plan_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pricing_plan_id' => 'Cannot select both pricing plan and reseller credit pack.']);
            }
            
            $creditPack = \App\Models\ResellerCreditPack::findOrFail($validated['reseller_credit_pack_id']);
            $amount = $validated['amount'] ?? $creditPack->price;
            $expiresAt = null; // Credit pack orders don't expire
            $orderType = 'credit_pack';
        } else {
            // Validate that reseller_credit_pack_id is not set when pricing_plan_id is set
            if (!empty($validated['reseller_credit_pack_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['reseller_credit_pack_id' => 'Cannot select both pricing plan and reseller credit pack.']);
            }
            
            if (empty($validated['pricing_plan_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pricing_plan_id' => 'Please select either a pricing plan, reseller credit pack, or custom product.']);
            }
            
            $pricingPlan = PricingPlan::findOrFail($validated['pricing_plan_id']);
            $amount = $validated['amount'] ?? $pricingPlan->price;
            
            // Calculate expiry date if not provided
            $expiresAt = $validated['expires_at'] ?? null;
            if (!$expiresAt && $validated['starts_at']) {
                $startsAt = \Carbon\Carbon::parse($validated['starts_at']);
                $expiresAt = $startsAt->copy()->addMonths($pricingPlan->duration_months);
            } elseif (!$expiresAt) {
                $expiresAt = now()->addMonths($pricingPlan->duration_months);
            }
            
            $orderType = 'subscription';
        }

        // Generate unique order number
        $orderNumber = match($orderType) {
            'credit_pack' => 'CP-' . strtoupper(uniqid()),
            'custom_product' => 'CUST-' . strtoupper(uniqid()),
            default => 'ORD-' . strtoupper(uniqid())
        };

        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => $validated['user_id'],
            'payment_method' => $validated['payment_method'],
            'status' => $validated['status'],
            'amount' => $amount,
            'starts_at' => $validated['starts_at'] ?? now(),
            'expires_at' => $expiresAt,
            'notes' => $validated['admin_notes'] ?? null,
            'order_type' => $orderType,
            'source' => $validated['source'] ?? null,
        ];
        
        // Set product type based on order type
        if ($isCustomProductOrder) {
            $orderData['custom_product_id'] = $validated['custom_product_id'];
        } elseif ($isResellerOrder) {
            $orderData['reseller_credit_pack_id'] = $validated['reseller_credit_pack_id'];
            // Store credit pack ID in pricing_plan_id for backward compatibility
            $orderData['pricing_plan_id'] = $validated['reseller_credit_pack_id'];
        } else {
            $orderData['pricing_plan_id'] = $validated['pricing_plan_id'];
        }

        // Process credentials if provided
        // Skip credentials for custom product orders
        if ($orderType !== 'custom_product') {
            // Handle multi-device credentials for client orders
            if ($user->role === 'client' && $request->has('devices') && is_array($request->input('devices'))) {
                $pricingPlan = $isResellerOrder ? null : PricingPlan::find($validated['pricing_plan_id'] ?? null);
                $deviceCount = $pricingPlan ? $pricingPlan->device_count : 1;
                $inputDevices = $request->input('devices');
                
                // Prepare devices data
                $devices = [];
                foreach ($inputDevices as $deviceIndex => $deviceData) {
                    if (is_array($deviceData)) {
                        $devices[] = [
                            'device_number' => $deviceIndex,
                            'username' => $deviceData['username'] ?? '',
                            'password' => $deviceData['password'] ?? '',
                            'url' => $deviceData['url'] ?? '',
                        ];
                    }
                }
                
                // For backward compatibility, set the first device as main subscription
                $firstDevice = $devices[0] ?? null;
                
                if ($firstDevice) {
                    $orderData['subscription_username'] = $firstDevice['username'] ?? null;
                    $orderData['subscription_password'] = $firstDevice['password'] ?? null;
                    $orderData['subscription_url'] = $firstDevice['url'] ?? null;
                }
                
                // Set devices array
                $orderData['devices'] = $devices;
            } elseif ($user->role === 'client' && ($validated['subscription_username'] ?? null)) {
                // Single device credentials (backward compatibility)
                $orderData['subscription_username'] = $validated['subscription_username'] ?? null;
                $orderData['subscription_password'] = $validated['subscription_password'] ?? null;
                $orderData['subscription_url'] = $validated['subscription_url'] ?? null;
            }
            
            // Handle reseller credentials
            if (($user->role === 'reseller' || $isResellerOrder) && ($validated['reseller_username'] ?? null)) {
                $orderData['reseller_username'] = $validated['reseller_username'] ?? null;
                $orderData['reseller_password'] = $validated['reseller_password'] ?? null;
                $orderData['reseller_login_url'] = $validated['reseller_login_url'] ?? null;
            }
        }

        $order = Order::create($orderData);

        // Dispatch OrderCreated event to send admin notification emails
        \App\Events\OrderCreated::dispatch($order);

        // Send emails if requested
        if ($request->boolean('send_order_confirmation')) {
            // Send appropriate order confirmation email based on order type
            if ($orderType === 'custom_product') {
                Mail::to($user->email)->send(new \App\Mail\CustomProductOrderMail($order));
            } else {
                Mail::to($user->email)->send(new \App\Mail\OrderConfirmationMail($order));
            }
        }

        if ($request->boolean('send_payment_instructions') && $validated['status'] === 'pending' && $orderType !== 'custom_product') {
            // Send payment instructions email (not for custom products)
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
        $sources = Source::orderBy('name')->get();
        return view('admin.orders.edit', compact('order', 'sources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Log all request data for debugging
        Log::info('Order update request', [
            'order_id' => $order->id,
            'all_input' => $request->all(),
            'has_devices' => $request->has('devices'),
            'devices_input' => $request->input('devices'),
        ]);
        
        $validationRules = [
            'status' => 'required|in:pending,active,expired,cancelled,completed',
            'amount' => 'required|numeric|min:0',
            'pricing_plan_id' => 'nullable|exists:pricing_plans,id',
            'reseller_credit_pack_id' => 'nullable|exists:reseller_credit_packs,id',
            'source' => 'nullable|string|max:255',
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
            'devices.*.url' => 'nullable|string|max:255',
            'reseller_username' => 'nullable|string|max:255',
            'reseller_password' => 'nullable|string|max:255',
            'reseller_login_url' => 'nullable|url|max:255',
        ];
        
        $validated = $request->validate($validationRules);
        
        Log::info('Order update validated', [
            'validated_data' => $validated,
            'has_devices_in_validated' => isset($validated['devices']),
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

        // Process multi-device data if present (same logic as activate method)
        if ($request->has('devices') && is_array($request->input('devices'))) {
            $deviceCount = $order->pricingPlan ? $order->pricingPlan->device_count : 1;
            $inputDevices = $request->input('devices');
            
            // Prepare devices data (same as activate method)
            $devices = [];
            foreach ($inputDevices as $deviceIndex => $deviceData) {
                if (is_array($deviceData)) {
                    $devices[] = [
                        'device_number' => $deviceIndex,
                        'username' => $deviceData['username'] ?? '',
                        'password' => $deviceData['password'] ?? '',
                        'url' => $deviceData['url'] ?? '',
                    ];
                }
            }
            
            // For backward compatibility, set the first device as main subscription
            $firstDevice = $devices[0] ?? null;
            
            if ($firstDevice) {
                $validated['subscription_username'] = $firstDevice['username'] ?? null;
                $validated['subscription_password'] = $firstDevice['password'] ?? null;
                $validated['subscription_url'] = $firstDevice['url'] ?? null;
            }
            
            // Set devices array
            $validated['devices'] = $devices;
            
            Log::info('Devices processed for update', [
                'devices' => $devices,
                'device_count' => count($devices),
            ]);
        }

        Log::info('Before order update', [
            'order_id' => $order->id,
            'data_to_update' => $validated,
            'current_devices' => $order->devices,
            'validated_keys' => array_keys($validated),
        ]);
        
        // Filter out non-fillable fields and ensure all validated data is included
        $updateData = [];
        $fillable = $order->getFillable();
        
        foreach ($validated as $key => $value) {
            if (in_array($key, $fillable)) {
                $updateData[$key] = $value;
            }
        }
        
        Log::info('Filtered update data', [
            'update_data' => $updateData,
            'update_data_keys' => array_keys($updateData),
            'fillable_fields' => $fillable,
        ]);
        
        // Update the order - Laravel will only update changed fields
        $order->fill($updateData);
        $order->save();
        
        Log::info('Update result', [
            'order_was_changed' => $order->wasChanged(),
            'order_changes' => $order->getChanges(),
        ]);
        
        // Refresh order to get updated data
        $order->refresh();
        
        Log::info('After order update', [
            'order_id' => $order->id,
            'updated_devices' => $order->devices,
            'updated_subscription_username' => $order->subscription_username,
            'updated_status' => $order->status,
            'updated_amount' => $order->amount,
        ]);

        // Send emails if requested
        if ($request->boolean('send_status_update')) {
            Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdateMail($order));
        }

        // If credentials are provided and not sent yet, send them
        if ($request->boolean('send_credentials') &&
            ($order->subscription_username || $order->reseller_username || $order->user->reseller_panel_url) &&
            $order->status === 'active') {
            $this->sendCredentials($order);
        }

        // Auto-send credentials for reseller orders when activated
        // Skip for custom product orders as they don't have credentials
        if ($order->order_type !== 'custom_product' &&
            $order->status === 'active' &&
            (($order->pricingPlan && $order->pricingPlan->plan_type === 'reseller') || $order->user->role === 'reseller') &&
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
        try {
            // Skip for custom product orders
            if ($order->order_type === 'custom_product') {
                return redirect()->back()
                    ->with('error', 'Custom product orders do not have credentials to send.');
            }
            
            if ($order->user->role === 'reseller' || ($order->pricingPlan && $order->pricingPlan->plan_type === 'reseller')) {
            // Use the new ResellerCredentialsMail for reseller orders
                $resellerMail = new ResellerCredentialsMail($order, $order->user);
                if ($resellerMail->mailerName) {
                    Mail::mailer($resellerMail->mailerName)->to($order->user->email)->send($resellerMail);
                } else {
                    Mail::to($order->user->email)->send($resellerMail);
                }

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

                $clientMail = new ClientCredentialsMail($order->user, $processedOrders);
                if ($clientMail->mailerName) {
                    Mail::mailer($clientMail->mailerName)->to($order->user->email)->send($clientMail);
                } else {
                    Mail::to($order->user->email)->send($clientMail);
                }
        }

        $order->update([
            'credentials_sent' => true,
            'credentials_sent_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Credentials sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send credentials email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to send credentials email: ' . $e->getMessage());
        }
    }

    /**
     * Send IPTV panel credentials for credit pack orders
     */
    public function sendCreditPackCredentials(Order $order)
    {
        // Send credentials email for credit pack orders using Mailable
        $creditPackMail = new \App\Mail\CreditPackCredentialsMail($order, $order->user);
        if ($creditPackMail->mailerName) {
            Mail::mailer($creditPackMail->mailerName)->to($order->user->email)->send($creditPackMail);
        } else {
            Mail::to($order->user->email)->send($creditPackMail);
        }

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

        // Check if this is a custom product order
        if ($order->order_type === 'custom_product') {
            // Validate custom product activation
            $validated = $request->validate([
                'email_subject' => 'required|string|max:255',
                'email_content' => 'required|string',
                'send_email' => 'boolean',
            ]);

            // Activate the order
            $order->update([
                'status' => 'active',
                'completed_at' => now(),
            ]);

            // Send custom composed email if requested
            if ($request->boolean('send_email', true)) {
                try {
                    $sourceMailService = new \App\Services\SourceMailService();
                    $success = $sourceMailService->sendCustomComposedEmail(
                        $order,
                        $validated['email_subject'],
                        $validated['email_content'],
                        true // Include footer
                    );

                    if ($success) {
                        return redirect()->back()
                            ->with('success', 'Custom product order activated successfully! Update email has been sent to the customer.');
                    } else {
                        return redirect()->back()
                            ->with('warning', 'Custom product order activated successfully, but there was an issue sending the email. Please send it manually.');
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send custom product update email', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return redirect()->back()
                        ->with('warning', 'Custom product order activated successfully, but there was an issue sending the email. Please send it manually.');
                }
            }

            return redirect()->back()
                ->with('success', 'Custom product order activated successfully. You can send the update email manually if needed.');
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

                    return redirect()->back()
                        ->with('success', 'Credit pack order activated successfully! IPTV panel credentials have been sent to the reseller via email.');
                } catch (\Exception $e) {
                    Log::error('Failed to send credit pack credentials email', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
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
                    $resellerMail = new ResellerCredentialsMail($order, $order->user);
                    if ($resellerMail->mailerName) {
                        Mail::mailer($resellerMail->mailerName)->to($order->user->email)->send($resellerMail);
                    } else {
                        Mail::to($order->user->email)->send($resellerMail);
                    }

                    // Trigger the reseller order activated event
                    ResellerOrderActivated::dispatch($order);

                    $order->update([
                        'credentials_sent' => true,
                        'credentials_sent_at' => now()
                    ]);

                    return redirect()->back()
                        ->with('success', 'Reseller order activated successfully! Reseller panel credentials have been sent to the customer via email.');
                } catch (\Exception $e) {
                    Log::error('Failed to send reseller credentials email', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
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
                        $renewalMail = new \App\Mail\AccountRenewedMail($order, $originalOrder);
                        if ($renewalMail->mailerName) {
                            Mail::mailer($renewalMail->mailerName)->to($order->user->email)->send($renewalMail);
                        } else {
                            Mail::to($order->user->email)->send($renewalMail);
                        }
                    } else {
                        // Send regular credentials email for new orders
                        $clientMail = new \App\Mail\ClientCredentialsMail($order->user, collect([$order]));
                        if ($clientMail->mailerName) {
                            Mail::mailer($clientMail->mailerName)->to($order->user->email)->send($clientMail);
                        } else {
                            Mail::to($order->user->email)->send($clientMail);
                        }
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
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
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
