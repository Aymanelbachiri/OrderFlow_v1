<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'reseller')
                    ->withCount([
                        'orders as credit_pack_orders_count' => function($q) {
                            $q->where('order_type', 'credit_pack');
                        },
                        'orders as pending_credit_orders_count' => function($q) {
                            $q->where('order_type', 'credit_pack')->where('status', 'pending');
                        }
                    ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->whereNull('suspended_at');
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false)->orWhereNotNull('suspended_at');
            }
        }

        $resellers = $query->latest()->paginate(20);

        return view('admin.resellers.index', compact('resellers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.resellers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'reseller_panel_url' => 'nullable|url|max:255',
            'reseller_panel_username' => 'nullable|string|max:255',
            'reseller_panel_password' => 'nullable|string|max:255',
            'email_verified' => 'nullable',
        ]);

        $reseller = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => null, // Resellers don't need passwords as they won't login
            'role' => 'reseller',
            'email_verified_at' => $request->has('email_verified') && $request->input('email_verified') == '1' ? now() : null,
            'reseller_panel_url' => $validated['reseller_panel_url'] ?? null,
            'reseller_panel_username' => $validated['reseller_panel_username'] ?? null,
            'reseller_panel_password' => $validated['reseller_panel_password'] ?? null,
        ]);

        $reseller->assignRole('reseller');

        return redirect()->route('admin.resellers.index')
            ->with('success', 'Reseller created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $reseller)
    {
        $reseller->load(['orders.pricingPlan', 'orders.resellerCreditPack']);

        // Calculate reseller statistics
        $totalOrders = $reseller->orders->count();
        $activeOrders = $reseller->orders->where('status', 'active')->count();
        $creditPackOrders = $reseller->orders->where('order_type', 'credit_pack')->count();
        $pendingOrders = $reseller->orders->where('status', 'pending')->count();

        return view('admin.resellers.show', compact(
            'reseller',
            'totalOrders',
            'activeOrders',
            'creditPackOrders',
            'pendingOrders'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $reseller)
    {
        return view('admin.resellers.edit', compact('reseller'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $reseller)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $reseller->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'suspension_reason' => 'nullable|string',
            'reseller_panel_url' => 'nullable|url|max:255',
            'reseller_panel_username' => 'nullable|string|max:255',
            'reseller_panel_password' => 'nullable|string|max:255',
            'available_credits' => 'nullable|integer|min:0',
            'email_verified' => 'nullable|boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $request->boolean('is_active'),
            'reseller_panel_url' => $validated['reseller_panel_url'],
            'reseller_panel_username' => $validated['reseller_panel_username'],
            'reseller_panel_password' => $validated['reseller_panel_password'],
            'available_credits' => $validated['available_credits'] ?? 0,
            'email_verified_at' => $request->has('email_verified') && $request->input('email_verified') == '1' ? now() : null,
            'password' => null, // Resellers don't need passwords as they won't login
        ];

        if (!$request->boolean('is_active')) {
            $updateData['suspended_at'] = now();
            $updateData['suspension_reason'] = $validated['suspension_reason'];
        } else {
            $updateData['suspended_at'] = null;
            $updateData['suspension_reason'] = null;
        }

        $reseller->update($updateData);

        return redirect()->route('admin.resellers.index')
            ->with('success', 'Reseller updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $reseller)
    {
        $reseller->delete();

        return redirect()->route('admin.resellers.index')
            ->with('success', 'Reseller deleted successfully.');
    }

    /**
     * Suspend a reseller account
     */
    public function suspend(Request $request, User $reseller)
    {
        $validated = $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        $reseller->update([
            'is_active' => false,
            'suspended_at' => now(),
            'suspension_reason' => $validated['suspension_reason'],
        ]);

        return redirect()->back()
            ->with('success', 'Reseller account suspended successfully.');
    }

    /**
     * Reactivate a reseller account
     */
    public function reactivate(User $reseller)
    {
        $reseller->update([
            'is_active' => true,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Reseller account reactivated successfully.');
    }

    /**
     * Toggle reseller account status
     */
    public function toggleStatus(User $reseller)
    {
        $reseller->update([
            'is_active' => !$reseller->is_active,
        ]);

        $status = $reseller->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Reseller account {$status} successfully.");
    }

    /**
     * Send password reset email to reseller
     */
    public function sendPasswordReset(User $reseller)
    {
        // Generate password reset token
        $token = \Illuminate\Support\Str::random(64);

        // Store the token (you might want to create a password_resets table entry)
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $reseller->email],
            [
                'email' => $reseller->email,
                'token' => \Illuminate\Support\Facades\Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send password reset email
        \Illuminate\Support\Facades\Mail::to($reseller->email)->send(
            new \App\Mail\PasswordResetMail($reseller, $token)
        );

        return redirect()->back()
            ->with('success', 'Password reset email sent successfully.');
    }
}
