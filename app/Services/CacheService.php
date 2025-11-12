<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\BlogPost;

class CacheService
{
    /**
     * Cache duration constants
     */
    const SHORT_CACHE = 300; // 5 minutes
    const MEDIUM_CACHE = 1800; // 30 minutes
    const LONG_CACHE = 3600; // 1 hour
    const DAILY_CACHE = 86400; // 24 hours

    /**
     * Get cached dashboard statistics
     */
    public static function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', self::MEDIUM_CACHE, function () {
            return [
                'total_users' => User::count(),
                'total_clients' => User::where('role', 'client')->count(),
                'total_resellers' => User::where('role', 'reseller')->count(),
                'active_orders' => Order::where('status', 'active')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'total_revenue' => Order::whereIn('status', ['active', 'completed'])->sum('amount'),
                'monthly_revenue' => Order::whereIn('status', ['active', 'completed'])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
            ];
        });
    }

    /**
     * Get cached pricing plans
     */
    public static function getActivePricingPlans(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('active_pricing_plans', self::LONG_CACHE, function () {
            return PricingPlan::where('is_active', true)
                ->orderBy('server_type')
                ->orderBy('device_count')
                ->orderBy('duration_months')
                ->get();
        });
    }

    /**
     * Get cached blog posts
     */
    public static function getPublishedBlogPosts(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "published_blog_posts_{$limit}";
        
        return Cache::remember($cacheKey, self::MEDIUM_CACHE, function () use ($limit) {
            return BlogPost::where('is_published', true)
                ->where('published_at', '<=', now())
                ->with('author:id,name')
                ->latest('published_at')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get cached user orders
     */
    public static function getUserOrders(int $userId, int $limit = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Don't cache paginated results, but cache the query data
        $cacheKey = "user_orders_data_{$userId}";
        
        $orders = Cache::remember($cacheKey, self::SHORT_CACHE, function () use ($userId) {
            return Order::where('user_id', $userId)
                ->with(['pricingPlan'])
                ->latest()
                ->get();
        });

        // Return paginated results from cached data
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $orders->take($limit),
            $orders->count(),
            $limit,
            request()->get('page', 1),
            ['path' => request()->url()]
        );
    }

    /**
     * Get cached system settings
     */
    public static function getSystemSettings(): array
    {
        return Cache::remember('system_settings', self::DAILY_CACHE, function () {
            return DB::table('system_settings')
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get cached popular pricing plans
     */
    public static function getPopularPricingPlans(int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "popular_pricing_plans_{$limit}";
        
        return Cache::remember($cacheKey, self::LONG_CACHE, function () use ($limit) {
            return PricingPlan::where('is_active', true)
                ->withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Clear specific cache keys
     */
    public static function clearDashboardCache(): void
    {
        Cache::forget('dashboard_stats');
    }

    public static function clearPricingCache(): void
    {
        Cache::forget('active_pricing_plans');
        // Clear popular plans cache with different limits
        for ($i = 1; $i <= 20; $i++) {
            Cache::forget("popular_pricing_plans_{$i}");
        }
    }

    public static function clearBlogCache(): void
    {
        // Clear blog cache with different limits
        for ($i = 1; $i <= 50; $i++) {
            Cache::forget("published_blog_posts_{$i}");
        }
    }

    public static function clearUserCache(int $userId): void
    {
        Cache::forget("user_orders_data_{$userId}");
    }

    public static function clearSystemSettingsCache(): void
    {
        Cache::forget('system_settings');
    }

    /**
     * Clear all application caches
     */
    public static function clearAllCache(): void
    {
        Cache::flush();
    }

    /**
     * Warm up essential caches
     */
    public static function warmUpCache(): void
    {
        // Warm up dashboard stats
        self::getDashboardStats();
        
        // Warm up pricing plans
        self::getActivePricingPlans();
        self::getPopularPricingPlans();
        
        // Warm up blog posts
        self::getPublishedBlogPosts();
        
        // Warm up system settings
        self::getSystemSettings();
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats(): array
    {
        $cacheKeys = [
            'dashboard_stats',
            'active_pricing_plans',
            'published_blog_posts_10',
            'popular_pricing_plans_6',
            'system_settings',
        ];

        $stats = [
            'total_keys' => 0,
            'cached_keys' => 0,
            'cache_hit_rate' => 0,
        ];

        foreach ($cacheKeys as $key) {
            $stats['total_keys']++;
            if (Cache::has($key)) {
                $stats['cached_keys']++;
            }
        }

        $stats['cache_hit_rate'] = $stats['total_keys'] > 0 
            ? round(($stats['cached_keys'] / $stats['total_keys']) * 100, 2) 
            : 0;

        return $stats;
    }

    /**
     * Cache expensive database queries
     */
    public static function cacheExpensiveQuery(string $key, callable $callback, int $duration = self::MEDIUM_CACHE)
    {
        return Cache::remember($key, $duration, $callback);
    }

    /**
     * Get or set cache with tags (if using Redis/Memcached)
     */
    public static function taggedCache(array $tags, string $key, callable $callback, int $duration = self::MEDIUM_CACHE)
    {
        if (config('cache.default') === 'redis' || config('cache.default') === 'memcached') {
            return Cache::tags($tags)->remember($key, $duration, $callback);
        }
        
        // Fallback to regular cache if tags not supported
        return Cache::remember($key, $duration, $callback);
    }

    /**
     * Invalidate cache by tags
     */
    public static function invalidateByTags(array $tags): void
    {
        if (config('cache.default') === 'redis' || config('cache.default') === 'memcached') {
            Cache::tags($tags)->flush();
        }
    }

    /**
     * Cache user-specific data
     */
    public static function cacheUserData(int $userId, string $key, callable $callback, int $duration = self::SHORT_CACHE)
    {
        $cacheKey = "user_{$userId}_{$key}";
        return Cache::remember($cacheKey, $duration, $callback);
    }

    /**
     * Clear user-specific cache
     */
    public static function clearUserData(int $userId): void
    {
        $pattern = "user_{$userId}_*";
        
        // This is a simplified approach - in production, you might want to use Redis SCAN
        $commonKeys = ['orders', 'profile', 'payments', 'statistics'];
        
        foreach ($commonKeys as $key) {
            Cache::forget("user_{$userId}_{$key}");
        }
    }
}
