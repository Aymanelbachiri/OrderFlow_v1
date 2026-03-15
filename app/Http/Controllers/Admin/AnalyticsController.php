<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\PricingPlan;
use App\Models\ResellerCreditPack;
use App\Models\CustomProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    use \App\Traits\SourceScopeable;

    public function index(Request $request)
    {
        $dateRange = $request->get('range', '30'); // Default to last 30 days
        $startDate = $this->getStartDate($dateRange);
        $endDate = now();

        // Revenue by Source (Client vs Reseller)
        $revenueBySource = $this->getRevenueBySource($startDate, $endDate);
        
        // Revenue by Payment Method
        $revenueByPaymentMethod = $this->getRevenueByPaymentMethod($startDate, $endDate);
        
        // Most Purchased Plans/Packs
        $popularPlans = $this->getPopularPlans($startDate, $endDate);
        $popularCreditPacks = $this->getPopularCreditPacks($startDate, $endDate);
        $popularCustomProducts = $this->getPopularCustomProducts($startDate, $endDate);
        
        // Top Clients/Resellers by Revenue
        $topClients = $this->getTopClients($startDate, $endDate);
        $topResellers = $this->getTopResellers($startDate, $endDate);
        
        // Revenue Trends (Daily/Weekly/Monthly)
        $revenueTrends = $this->getRevenueTrends($startDate, $endDate, $dateRange);
        
        // Order Status Distribution
        $orderStatusDistribution = $this->getOrderStatusDistribution($startDate, $endDate);
        
        // Geographic Distribution (if we had location data)
        $geographicDistribution = $this->getGeographicDistribution($startDate, $endDate);
        
        // Summary Statistics
        $summaryStats = $this->getSummaryStats($startDate, $endDate);

        return view('admin.analytics.index', compact(
            'dateRange',
            'startDate',
            'endDate',
            'revenueBySource',
            'revenueByPaymentMethod',
            'popularPlans',
            'popularCreditPacks',
            'popularCustomProducts',
            'topClients',
            'topResellers',
            'revenueTrends',
            'orderStatusDistribution',
            'geographicDistribution',
            'summaryStats'
        ));
    }

    private function getStartDate($range)
    {
        switch ($range) {
            case '7':
                return now()->subDays(7);
            case '30':
                return now()->subDays(30);
            case '90':
                return now()->subDays(90);
            case '365':
                return now()->subDays(365);
            case 'all':
                return Order::min('created_at') ?? now()->subYear();
            default:
                return now()->subDays(30);
        }
    }

    private function getRevenueBySource($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed']);
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.role',
                DB::raw('SUM(orders.amount) as total_revenue'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
            ->groupBy('users.role')
            ->get()
            ->map(function ($item) {
                return [
                    'source' => ucfirst($item->role),
                    'revenue' => (float) $item->total_revenue,
                    'orders' => (int) $item->total_orders,
                    'percentage' => 0 // Will be calculated in the view
                ];
            });
    }

    private function getRevenueByPaymentMethod($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed']);
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->select(
                'orders.payment_method',
                DB::raw('SUM(orders.amount) as total_revenue'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
            ->groupBy('orders.payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => ucfirst($item->payment_method ?? 'Unknown'),
                    'revenue' => (float) $item->total_revenue,
                    'orders' => (int) $item->total_orders,
                    'percentage' => 0 // Will be calculated in the view
                ];
            });
    }

    private function getPopularPlans($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed'])
            ->whereNotNull('orders.pricing_plan_id')
            ->where('orders.order_type', '!=', 'credit_pack');
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->join('pricing_plans', 'orders.pricing_plan_id', '=', 'pricing_plans.id')
            ->select(
                'pricing_plans.name',
                'pricing_plans.price',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.amount) as total_revenue')
            )
            ->groupBy('pricing_plans.id', 'pricing_plans.name', 'pricing_plans.price')
            ->orderBy('order_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getPopularCreditPacks($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed'])
            ->where('orders.order_type', 'credit_pack');
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->join('reseller_credit_packs', 'orders.pricing_plan_id', '=', 'reseller_credit_packs.id')
            ->select(
                'reseller_credit_packs.name',
                'reseller_credit_packs.price',
                'reseller_credit_packs.credits_amount',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.amount) as total_revenue')
            )
            ->groupBy('reseller_credit_packs.id', 'reseller_credit_packs.name', 'reseller_credit_packs.price', 'reseller_credit_packs.credits_amount')
            ->orderBy('order_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getPopularCustomProducts($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed'])
            ->where('orders.order_type', 'custom_product')
            ->whereNotNull('orders.custom_product_id');
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->join('custom_products', 'orders.custom_product_id', '=', 'custom_products.id')
            ->select(
                'custom_products.name',
                'custom_products.price',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.amount) as total_revenue')
            )
            ->groupBy('custom_products.id', 'custom_products.name', 'custom_products.price')
            ->orderBy('order_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getTopClients($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed'])
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('users.role', 'client');
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->select(
                'users.name',
                'users.email',
                DB::raw('SUM(orders.amount) as total_spent'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
    }

    private function getTopResellers($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed'])
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('users.role', 'reseller');
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->select(
                'users.name',
                'users.email',
                DB::raw('SUM(orders.amount) as total_spent'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
    }

    private function getRevenueTrends($startDate, $endDate, $range)
    {
        $trends = [];
        
        if ($range === '7') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $revenue = $this->scopeBySource(Order::whereIn('status', ['active', 'completed'])
                    ->whereDate('created_at', $date))->sum('amount');
                
                $trends[] = [
                    'date' => $date->format('M d'),
                    'revenue' => (float) $revenue,
                    'orders' => $this->scopeBySource(Order::whereDate('created_at', $date))->count()
                ];
            }
        } elseif ($range === '30') {
            for ($i = 3; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekEnd = now()->subWeeks($i)->endOfWeek();
                
                $revenue = $this->scopeBySource(Order::whereIn('status', ['active', 'completed'])
                    ->whereBetween('created_at', [$weekStart, $weekEnd]))->sum('amount');
                
                $trends[] = [
                    'date' => $weekStart->format('M d') . ' - ' . $weekEnd->format('M d'),
                    'revenue' => (float) $revenue,
                    'orders' => $this->scopeBySource(Order::whereBetween('created_at', [$weekStart, $weekEnd]))->count()
                ];
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $revenue = $this->scopeBySource(Order::whereIn('status', ['active', 'completed'])
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year))->sum('amount');
                
                $trends[] = [
                    'date' => $date->format('M Y'),
                    'revenue' => (float) $revenue,
                    'orders' => $this->scopeBySource(Order::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year))->count()
                ];
            }
        }
        
        return $trends;
    }

    private function getOrderStatusDistribution($startDate, $endDate)
    {
        $query = Order::whereBetween('orders.created_at', [$startDate, $endDate]);
        $this->scopeBySource($query, 'orders.source');
        return $query
            ->select(
                'orders.status',
                DB::raw('COUNT(orders.id) as count'),
                DB::raw('SUM(orders.amount) as total_amount')
            )
            ->groupBy('orders.status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->status),
                    'count' => (int) $item->count,
                    'amount' => (float) $item->total_amount,
                    'percentage' => 0 // Will be calculated in the view
                ];
            });
    }

    private function getGeographicDistribution($startDate, $endDate)
    {
        // Since we don't have geographic data, we'll return empty for now
        // This could be enhanced if we add country/region fields to users or orders
        return collect([]);
    }

    private function getSummaryStats($startDate, $endDate)
    {
        $totalRevenue = $this->scopeBySource(Order::whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['active', 'completed']), 'orders.source')->sum('orders.amount');
            
        $totalOrders = $this->scopeBySource(Order::whereBetween('orders.created_at', [$startDate, $endDate]), 'orders.source')->count();
        
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        $newCustomers = $this->scopeBySource(User::whereBetween('users.created_at', [$startDate, $endDate]))->count();
        
        $allowedSources = auth()->user()->getAllowedSourceNames();
        $repeatCustomers = User::whereHas('orders', function ($query) use ($startDate, $endDate, $allowedSources) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
            if ($allowedSources !== null) {
                $query->whereIn('source', $allowedSources);
            }
        })
        ->whereHas('orders', function ($query) use ($startDate, $allowedSources) {
            $query->where('created_at', '<', $startDate);
            if ($allowedSources !== null) {
                $query->whereIn('source', $allowedSources);
            }
        })
        ->count();

        return [
            'total_revenue' => (float) $totalRevenue,
            'total_orders' => (int) $totalOrders,
            'avg_order_value' => (float) $avgOrderValue,
            'new_customers' => (int) $newCustomers,
            'repeat_customers' => (int) $repeatCustomers,
            'conversion_rate' => $newCustomers > 0 ? round(($repeatCustomers / $newCustomers) * 100, 2) : 0
        ];
    }
}