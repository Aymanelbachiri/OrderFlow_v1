<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use App\Services\PerformanceService;

class DashboardController extends Controller
{
    use \App\Traits\SourceScopeable;

    public function index()
    {
        $totalRevenue = $this->scopeBySource(Order::whereIn('status', ['active', 'completed']))->sum('amount');
        $monthlyRevenue = $this->scopeBySource(Order::whereIn('status', ['active', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year))->sum('amount');

        $activeClients = $this->scopeBySource(User::where('role', 'client')
            ->where('is_active', true))->count();

        $activeResellers = $this->scopeBySource(User::where('role', 'reseller')
            ->where('is_active', true))->count();

        $expiringSubscriptions = $this->scopeBySource(Order::whereNotNull('expires_at')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', now()->addDays(7)))->count();

        $pendingOrders = $this->scopeBySource(Order::where('status', 'pending'))->count();

        $recentOrdersQuery = Order::with(['user', 'pricingPlan', 'resellerCreditPack'])->latest();
        $this->scopeBySource($recentOrdersQuery);
        $recentOrders = $recentOrdersQuery->take(10)->get();

        $monthlyRevenueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = $this->scopeBySource(Order::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year))->sum('amount');

            $monthlyRevenueData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'monthlyRevenue',
            'activeClients',
            'activeResellers',
            'expiringSubscriptions',
            'pendingOrders',
            'recentOrders',
            'monthlyRevenueData'
        ));
    }
}
