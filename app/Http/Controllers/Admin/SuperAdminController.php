<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Super Admin Dashboard - View all admins and their data
     */
    public function dashboard()
    {
        $admins = User::where('role', 'admin')
            ->with(['adminPermissions', 'adminConfig'])
            ->withCount([
                'adminOrders',
                'adminSources',
                'adminCustomProducts',
                'adminPricingPlans',
                'adminResellerCreditPacks',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'active')->sum('amount');

        return view('admin.super.dashboard', compact('admins', 'totalOrders', 'totalRevenue'));
    }

    /**
     * View all orders from all admins
     */
    public function allOrders(Request $request)
    {
        $query = Order::with(['user', 'admin', 'pricingPlan']);

        // Filter by admin if specified
        if ($request->has('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(50);
        $admins = User::where('role', 'admin')->get();

        return view('admin.super.orders', compact('orders', 'admins'));
    }

    /**
     * View specific admin's data
     */
    public function viewAdmin(User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        $admin->load([
            'adminPermissions',
            'adminConfig',
            'adminOrders' => function($q) {
                $q->latest()->limit(20);
            },
            'adminSources',
            'adminCustomProducts',
            'adminPricingPlans',
            'adminResellerCreditPacks',
        ]);

        $stats = [
            'total_orders' => $admin->adminOrders()->count(),
            'active_orders' => $admin->adminOrders()->where('status', 'active')->count(),
            'total_revenue' => $admin->adminOrders()->where('status', 'active')->sum('amount'),
            'sources_count' => $admin->adminSources()->count(),
            'custom_products_count' => $admin->adminCustomProducts()->count(),
            'pricing_plans_count' => $admin->adminPricingPlans()->count(),
        ];

        return view('admin.super.view-admin', compact('admin', 'stats'));
    }
}
