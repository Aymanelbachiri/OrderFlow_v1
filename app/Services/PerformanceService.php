<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 60;

    /**
     * Get dashboard statistics with caching
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', self::CACHE_DURATION, function () {
            return [
                'total_users' => User::count(),
                'total_clients' => User::where('role', 'client')->count(),
                'total_resellers' => User::where('role', 'reseller')->count(),
                'total_orders' => Order::count(),
                'active_orders' => Order::where('status', 'active')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'expired_orders' => Order::where('status', 'expired')->count(),
                'total_revenue' => Order::whereIn('status', ['active', 'completed'])->sum('amount'),
                'monthly_revenue' => Order::whereIn('status', ['active', 'completed'])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
                'total_payments' => Order::whereIn('status', ['active', 'completed'])->count(),
                'total_blog_posts' => BlogPost::count(),
                'published_posts' => BlogPost::where('is_published', true)->count(),
            ];
        });
    }

    /**
     * Get user statistics with caching
     */
    public function getUserStats(int $userId): array
    {
        return Cache::remember("user_stats_{$userId}", self::CACHE_DURATION, function () use ($userId) {
            return [
                'total_orders' => Order::where('user_id', $userId)->count(),
                'active_orders' => Order::where('user_id', $userId)->where('status', 'active')->count(),
                'total_spent' => Order::where('user_id', $userId)->where('status', 'active')->sum('amount'),
                'monthly_spent' => Order::where('user_id', $userId)
                    ->where('status', 'active')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
            ];
        });
    }

    /**
     * Get expiring orders efficiently
     */
    public function getExpiringOrders(int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("expiring_orders_{$days}", 30, function () use ($days) {
            return Order::with(['user', 'pricingPlan'])
                ->where('status', 'active')
                ->where('expires_at', '<=', now()->addDays($days))
                ->where('expires_at', '>', now())
                ->orderBy('expires_at')
                ->get();
        });
    }

    /**
     * Get popular pricing plans
     */
    public function getPopularPlans(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('popular_plans', self::CACHE_DURATION * 2, function () {
            return DB::table('pricing_plans')
                ->select('pricing_plans.*', DB::raw('COUNT(orders.id) as order_count'))
                ->leftJoin('orders', 'pricing_plans.id', '=', 'orders.pricing_plan_id')
                ->where('pricing_plans.is_active', true)
                ->groupBy('pricing_plans.id')
                ->orderBy('order_count', 'desc')
                ->limit(10)
                ->get();
        });
    }

    /**
     * Get recent activity efficiently
     */
    public function getRecentActivity(int $limit = 10): array
    {
        return Cache::remember("recent_activity_{$limit}", 15, function () use ($limit) {
            $recentOrders = Order::with(['user', 'pricingPlan'])
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($order) {
                    return [
                        'type' => 'order',
                        'message' => "New order #{$order->order_number} by {$order->user->name}",
                        'created_at' => $order->created_at,
                        'data' => $order,
                    ];
                });

            $recentPayments = Payment::with(['order.user'])
                ->where('status', 'completed')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($payment) {
                    return [
                        'type' => 'payment',
                        'message' => "Payment of \${$payment->amount} received from {$payment->order->user->name}",
                        'created_at' => $payment->created_at,
                        'data' => $payment,
                    ];
                });

            return $recentOrders->concat($recentPayments)
                ->sortByDesc('created_at')
                ->take($limit)
                ->values()
                ->toArray();
        });
    }

    /**
     * Clear all performance caches
     */
    public function clearCaches(): void
    {
        $cacheKeys = [
            'dashboard_stats',
            'popular_plans',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear pattern-based caches
        $this->clearPatternCache('user_stats_*');
        $this->clearPatternCache('expiring_orders_*');
        $this->clearPatternCache('recent_activity_*');
    }

    /**
     * Clear cache by pattern (simplified version)
     */
    private function clearPatternCache(string $pattern): void
    {
        // This is a simplified implementation
        // In production, you might want to use Redis with pattern matching
        for ($i = 1; $i <= 1000; $i++) {
            $key = str_replace('*', $i, $pattern);
            Cache::forget($key);
        }
    }

    /**
     * Get database performance metrics
     */
    public function getDatabaseMetrics(): array
    {
        return Cache::remember('db_metrics', 5, function () {
            $metrics = [];

            try {
                // Get table sizes (MySQL specific)
                $tables = DB::select("
                    SELECT 
                        table_name,
                        table_rows,
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                    FROM information_schema.tables 
                    WHERE table_schema = DATABASE()
                    ORDER BY (data_length + index_length) DESC
                ");

                $metrics['tables'] = collect($tables)->map(function ($table) {
                    return [
                        'name' => $table->table_name,
                        'rows' => $table->table_rows,
                        'size_mb' => $table->size_mb,
                    ];
                });

                // Get slow query count (if available)
                $slowQueries = DB::select("SHOW GLOBAL STATUS LIKE 'Slow_queries'");
                $metrics['slow_queries'] = $slowQueries[0]->Value ?? 0;

                // Get connection count
                $connections = DB::select("SHOW GLOBAL STATUS LIKE 'Threads_connected'");
                $metrics['connections'] = $connections[0]->Value ?? 0;

            } catch (\Exception $e) {
                $metrics['error'] = 'Unable to fetch database metrics: ' . $e->getMessage();
            }

            return $metrics;
        });
    }

    /**
     * Optimize database tables
     */
    public function optimizeTables(): array
    {
        $results = [];
        
        try {
            $tables = ['users', 'orders', 'payments', 'pricing_plans', 'blog_posts', 'email_templates', 'system_settings'];
            
            foreach ($tables as $table) {
                DB::statement("OPTIMIZE TABLE {$table}");
                $results[] = "Optimized table: {$table}";
            }
            
            // Clear caches after optimization
            $this->clearCaches();
            
        } catch (\Exception $e) {
            $results[] = 'Error optimizing tables: ' . $e->getMessage();
        }

        return $results;
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        return [
            'driver' => config('cache.default'),
            'stores' => array_keys(config('cache.stores')),
            'status' => 'active',
        ];
    }
}
