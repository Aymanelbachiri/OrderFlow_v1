<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminPermission;
use App\Models\AdminConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->with(['adminPermissions', 'adminConfig', 'createdAdmins'])
            ->withCount([
                'adminOrders',
                'adminSources',
                'adminCustomProducts',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.super.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        return view('admin.super.admins.create');
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_super_admin' => 'boolean',
            // Permissions
            'can_manage_sources' => 'boolean',
            'can_create_custom_products' => 'boolean',
            'can_send_renewal_emails' => 'boolean',
            'can_manage_pricing_plans' => 'boolean',
            'can_manage_reseller_credit_packs' => 'boolean',
            'can_manage_payment_config' => 'boolean',
            'can_view_orders' => 'boolean',
            'can_manage_orders' => 'boolean',
            // Limits
            'max_sources' => 'nullable|integer|min:0',
            'max_custom_products' => 'nullable|integer|min:0',
            'max_reseller_credit_packs' => 'nullable|integer|min:0',
        ]);

        // Create admin user
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'is_super_admin' => $validated['is_super_admin'] ?? false,
            'created_by_admin_id' => auth()->id(),
        ]);

        // Create permissions
        AdminPermission::create([
            'admin_id' => $admin->id,
            'can_manage_sources' => $validated['can_manage_sources'] ?? false,
            'can_create_custom_products' => $validated['can_create_custom_products'] ?? false,
            'can_send_renewal_emails' => $validated['can_send_renewal_emails'] ?? false,
            'can_manage_pricing_plans' => $validated['can_manage_pricing_plans'] ?? true,
            'can_manage_reseller_credit_packs' => $validated['can_manage_reseller_credit_packs'] ?? false,
            'can_manage_payment_config' => $validated['can_manage_payment_config'] ?? true,
            'can_view_orders' => $validated['can_view_orders'] ?? true,
            'can_manage_orders' => $validated['can_manage_orders'] ?? true,
            'max_sources' => $validated['max_sources'] ?? null,
            'max_custom_products' => $validated['max_custom_products'] ?? null,
            'max_reseller_credit_packs' => $validated['max_reseller_credit_packs'] ?? null,
        ]);

        // Create default config
        AdminConfig::create([
            'admin_id' => $admin->id,
        ]);

        return redirect()->route('admin.super.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit(User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        $admin->load(['adminPermissions', 'adminConfig']);

        return view('admin.super.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_super_admin' => 'boolean',
            // Permissions
            'can_manage_sources' => 'boolean',
            'can_create_custom_products' => 'boolean',
            'can_send_renewal_emails' => 'boolean',
            'can_manage_pricing_plans' => 'boolean',
            'can_manage_reseller_credit_packs' => 'boolean',
            'can_manage_payment_config' => 'boolean',
            'can_view_orders' => 'boolean',
            'can_manage_orders' => 'boolean',
            // Limits
            'max_sources' => 'nullable|integer|min:0',
            'max_custom_products' => 'nullable|integer|min:0',
            'max_reseller_credit_packs' => 'nullable|integer|min:0',
        ]);

        // Update admin user
        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_super_admin' => $validated['is_super_admin'] ?? false,
        ]);

        if (!empty($validated['password'])) {
            $admin->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Update permissions
        $permissions = $admin->adminPermissions ?? AdminPermission::create(['admin_id' => $admin->id]);
        $permissions->update([
            'can_manage_sources' => $validated['can_manage_sources'] ?? false,
            'can_create_custom_products' => $validated['can_create_custom_products'] ?? false,
            'can_send_renewal_emails' => $validated['can_send_renewal_emails'] ?? false,
            'can_manage_pricing_plans' => $validated['can_manage_pricing_plans'] ?? true,
            'can_manage_reseller_credit_packs' => $validated['can_manage_reseller_credit_packs'] ?? false,
            'can_manage_payment_config' => $validated['can_manage_payment_config'] ?? true,
            'can_view_orders' => $validated['can_view_orders'] ?? true,
            'can_manage_orders' => $validated['can_manage_orders'] ?? true,
            'max_sources' => $validated['max_sources'] ?? null,
            'max_custom_products' => $validated['max_custom_products'] ?? null,
            'max_reseller_credit_packs' => $validated['max_reseller_credit_packs'] ?? null,
        ]);

        return redirect()->route('admin.super.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified admin
     */
    public function destroy(User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        // Don't allow deleting super admins
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.super.admins.index')
                ->with('error', 'Cannot delete super admin.');
        }

        $admin->delete();

        return redirect()->route('admin.super.admins.index')
            ->with('success', 'Admin deleted successfully.');
    }
}
