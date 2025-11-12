<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformanceMonitoringService
{
    /**
     * Monitor database query performance
     */
    public static function monitorDatabaseQueries(): array
    {
        $slowQueries = [];
        $queryCount = 0;
        $totalTime = 0;

        DB::listen(function ($query) use (&$slowQueries, &$queryCount, &$totalTime) {
            $queryCount++;
            $totalTime += $query->time;

            // Log slow queries (over 1 second)
            if ($query->time > 1000) {
                $slowQueries[] = [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ];

                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'time' => $query->time . 'ms',
                    'bindings' => $query->bindings,
                ]);
            }
        });

        return [
            'query_count' => $queryCount,
            'total_time' => $totalTime,
            'slow_queries' => $slowQueries,
            'average_time' => $queryCount > 0 ? $totalTime / $queryCount : 0,
        ];
    }

    /**
     * Monitor memory usage
     */
    public static function getMemoryUsage(): array
    {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'limit' => ini_get('memory_limit'),
            'current_formatted' => self::formatBytes(memory_get_usage(true)),
            'peak_formatted' => self::formatBytes(memory_get_peak_usage(true)),
        ];
    }

    /**
     * Monitor cache performance
     */
    public static function getCacheStats(): array
    {
        $cacheDriver = config('cache.default');
        $stats = [
            'driver' => $cacheDriver,
            'status' => 'unknown',
        ];

        try {
            // Test cache functionality
            $testKey = 'performance_test_' . time();
            Cache::put($testKey, 'test_value', 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);

            $stats['status'] = $retrieved === 'test_value' ? 'working' : 'error';
        } catch (\Exception $e) {
            $stats['status'] = 'error';
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Monitor application response times
     */
    public static function recordResponseTime(string $route, float $responseTime): void
    {
        $key = "response_times:{$route}";
        $times = Cache::get($key, []);
        
        // Keep only last 100 response times
        if (count($times) >= 100) {
            array_shift($times);
        }
        
        $times[] = [
            'time' => $responseTime,
            'timestamp' => now()->timestamp,
        ];
        
        Cache::put($key, $times, now()->addHours(24));

        // Log slow responses (over 2 seconds)
        if ($responseTime > 2000) {
            Log::warning('Slow response detected', [
                'route' => $route,
                'response_time' => $responseTime . 'ms',
            ]);
        }
    }

    /**
     * Get response time statistics for a route
     */
    public static function getResponseTimeStats(string $route): array
    {
        $key = "response_times:{$route}";
        $times = Cache::get($key, []);
        
        if (empty($times)) {
            return [
                'count' => 0,
                'average' => 0,
                'min' => 0,
                'max' => 0,
            ];
        }
        
        $responseTimes = array_column($times, 'time');
        
        return [
            'count' => count($responseTimes),
            'average' => array_sum($responseTimes) / count($responseTimes),
            'min' => min($responseTimes),
            'max' => max($responseTimes),
            'recent' => array_slice($responseTimes, -10), // Last 10 responses
        ];
    }

    /**
     * Monitor disk usage
     */
    public static function getDiskUsage(): array
    {
        $storagePath = storage_path();
        
        return [
            'total' => disk_total_space($storagePath),
            'free' => disk_free_space($storagePath),
            'used' => disk_total_space($storagePath) - disk_free_space($storagePath),
            'total_formatted' => self::formatBytes(disk_total_space($storagePath)),
            'free_formatted' => self::formatBytes(disk_free_space($storagePath)),
            'used_formatted' => self::formatBytes(disk_total_space($storagePath) - disk_free_space($storagePath)),
            'usage_percentage' => round(((disk_total_space($storagePath) - disk_free_space($storagePath)) / disk_total_space($storagePath)) * 100, 2),
        ];
    }

    /**
     * Get system load average (Unix systems only)
     */
    public static function getSystemLoad(): array
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return [
                '1_minute' => $load[0],
                '5_minutes' => $load[1],
                '15_minutes' => $load[2],
            ];
        }

        return [
            '1_minute' => null,
            '5_minutes' => null,
            '15_minutes' => null,
            'note' => 'Load average not available on this system',
        ];
    }

    /**
     * Generate performance report
     */
    public static function generatePerformanceReport(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'memory' => self::getMemoryUsage(),
            'cache' => self::getCacheStats(),
            'disk' => self::getDiskUsage(),
            'system_load' => self::getSystemLoad(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];
    }

    /**
     * Check system health
     */
    public static function checkSystemHealth(): array
    {
        $issues = [];
        $score = 100;

        // Check memory usage
        $memory = self::getMemoryUsage();
        $memoryUsagePercent = ($memory['current'] / self::parseBytes($memory['limit'])) * 100;
        
        if ($memoryUsagePercent > 90) {
            $issues[] = 'High memory usage: ' . round($memoryUsagePercent, 2) . '%';
            $score -= 20;
        } elseif ($memoryUsagePercent > 75) {
            $issues[] = 'Moderate memory usage: ' . round($memoryUsagePercent, 2) . '%';
            $score -= 10;
        }

        // Check disk usage
        $disk = self::getDiskUsage();
        if ($disk['usage_percentage'] > 90) {
            $issues[] = 'High disk usage: ' . $disk['usage_percentage'] . '%';
            $score -= 20;
        } elseif ($disk['usage_percentage'] > 80) {
            $issues[] = 'Moderate disk usage: ' . $disk['usage_percentage'] . '%';
            $score -= 10;
        }

        // Check cache status
        $cache = self::getCacheStats();
        if ($cache['status'] !== 'working') {
            $issues[] = 'Cache system not working properly';
            $score -= 15;
        }

        // Check database connectivity
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $issues[] = 'Database connection issue: ' . $e->getMessage();
            $score -= 30;
        }

        return [
            'score' => max(0, $score),
            'status' => $score >= 80 ? 'healthy' : ($score >= 60 ? 'warning' : 'critical'),
            'issues' => $issues,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Parse memory limit string to bytes
     */
    private static function parseBytes(string $size): int
    {
        $unit = strtoupper(substr($size, -1));
        $value = (int) substr($size, 0, -1);
        
        switch ($unit) {
            case 'G':
                return $value * 1024 * 1024 * 1024;
            case 'M':
                return $value * 1024 * 1024;
            case 'K':
                return $value * 1024;
            default:
                return (int) $size;
        }
    }
}
