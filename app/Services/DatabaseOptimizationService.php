<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseOptimizationService
{
    /**
     * Optimize database tables
     */
    public static function optimizeTables(): array
    {
        $results = [];
        $tables = self::getAllTables();

        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
                $results[$table] = 'optimized';
            } catch (\Exception $e) {
                $results[$table] = 'error: ' . $e->getMessage();
                Log::error("Failed to optimize table {$table}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Analyze database tables
     */
    public static function analyzeTables(): array
    {
        $results = [];
        $tables = self::getAllTables();

        foreach ($tables as $table) {
            try {
                DB::statement("ANALYZE TABLE {$table}");
                $results[$table] = 'analyzed';
            } catch (\Exception $e) {
                $results[$table] = 'error: ' . $e->getMessage();
                Log::error("Failed to analyze table {$table}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Get database size information
     */
    public static function getDatabaseSize(): array
    {
        $databaseName = config('database.connections.mysql.database');
        
        $query = "
            SELECT 
                table_name,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
                table_rows,
                ROUND((data_length / 1024 / 1024), 2) AS data_size_mb,
                ROUND((index_length / 1024 / 1024), 2) AS index_size_mb
            FROM information_schema.TABLES 
            WHERE table_schema = ?
            ORDER BY (data_length + index_length) DESC
        ";

        $tables = DB::select($query, [$databaseName]);
        
        $totalSize = array_sum(array_column($tables, 'size_mb'));
        
        return [
            'total_size_mb' => round($totalSize, 2),
            'tables' => $tables,
        ];
    }

    /**
     * Get slow queries (if slow query log is enabled)
     */
    public static function getSlowQueries(int $limit = 10): array
    {
        try {
            // This requires slow query log to be enabled
            $queries = DB::select("
                SELECT 
                    sql_text,
                    exec_count,
                    avg_timer_wait / 1000000000000 as avg_time_seconds,
                    sum_timer_wait / 1000000000000 as total_time_seconds
                FROM performance_schema.events_statements_summary_by_digest 
                WHERE schema_name = ?
                ORDER BY avg_timer_wait DESC 
                LIMIT ?
            ", [config('database.connections.mysql.database'), $limit]);

            return $queries;
        } catch (\Exception $e) {
            Log::warning("Could not retrieve slow queries: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check for missing indexes
     */
    public static function checkMissingIndexes(): array
    {
        $recommendations = [];

        // Check for foreign keys without indexes
        $foreignKeys = self::getForeignKeysWithoutIndexes();
        if (!empty($foreignKeys)) {
            $recommendations['foreign_keys_without_indexes'] = $foreignKeys;
        }

        // Check for frequently queried columns without indexes
        $frequentColumns = self::getFrequentlyQueriedColumns();
        if (!empty($frequentColumns)) {
            $recommendations['frequently_queried_without_indexes'] = $frequentColumns;
        }

        return $recommendations;
    }

    /**
     * Get table statistics
     */
    public static function getTableStatistics(): array
    {
        $databaseName = config('database.connections.mysql.database');
        
        $query = "
            SELECT 
                table_name,
                table_rows,
                avg_row_length,
                data_length,
                index_length,
                data_free,
                auto_increment,
                create_time,
                update_time,
                check_time
            FROM information_schema.TABLES 
            WHERE table_schema = ?
            ORDER BY table_rows DESC
        ";

        return DB::select($query, [$databaseName]);
    }

    /**
     * Clean up old data
     */
    public static function cleanupOldData(): array
    {
        $results = [];

        try {
            // Clean up old failed jobs (older than 7 days)
            $deletedJobs = DB::table('failed_jobs')
                ->where('failed_at', '<', now()->subDays(7))
                ->delete();
            $results['failed_jobs_cleaned'] = $deletedJobs;

            // Clean up old sessions (older than 30 days)
            if (Schema::hasTable('sessions')) {
                $deletedSessions = DB::table('sessions')
                    ->where('last_activity', '<', now()->subDays(30)->timestamp)
                    ->delete();
                $results['sessions_cleaned'] = $deletedSessions;
            }

            // Clean up old password reset tokens (older than 24 hours)
            $deletedTokens = DB::table('password_reset_tokens')
                ->where('created_at', '<', now()->subDay())
                ->delete();
            $results['password_tokens_cleaned'] = $deletedTokens;

            // Clean up expired orders (older than 1 year)
            $deletedOrders = DB::table('orders')
                ->where('status', 'expired')
                ->where('expires_at', '<', now()->subYear())
                ->delete();
            $results['expired_orders_cleaned'] = $deletedOrders;

        } catch (\Exception $e) {
            $results['error'] = $e->getMessage();
            Log::error("Database cleanup failed: " . $e->getMessage());
        }

        return $results;
    }

    /**
     * Get connection pool status
     */
    public static function getConnectionStatus(): array
    {
        try {
            $status = DB::select("SHOW STATUS LIKE 'Threads_%'");
            $variables = DB::select("SHOW VARIABLES LIKE 'max_connections'");
            
            $result = [];
            foreach ($status as $stat) {
                $result[$stat->Variable_name] = $stat->Value;
            }
            foreach ($variables as $var) {
                $result[$var->Variable_name] = $var->Value;
            }
            
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get all table names
     */
    private static function getAllTables(): array
    {
        $databaseName = config('database.connections.mysql.database');
        
        $tables = DB::select("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = ?
        ", [$databaseName]);

        return array_column($tables, 'table_name');
    }

    /**
     * Get foreign keys without indexes
     */
    private static function getForeignKeysWithoutIndexes(): array
    {
        $databaseName = config('database.connections.mysql.database');
        
        try {
            $query = "
                SELECT 
                    kcu.table_name,
                    kcu.column_name,
                    kcu.referenced_table_name,
                    kcu.referenced_column_name
                FROM information_schema.key_column_usage kcu
                LEFT JOIN information_schema.statistics s 
                    ON kcu.table_schema = s.table_schema 
                    AND kcu.table_name = s.table_name 
                    AND kcu.column_name = s.column_name
                WHERE kcu.table_schema = ?
                    AND kcu.referenced_table_name IS NOT NULL
                    AND s.column_name IS NULL
            ";

            return DB::select($query, [$databaseName]);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get frequently queried columns (simplified check)
     */
    private static function getFrequentlyQueriedColumns(): array
    {
        // This is a simplified implementation
        // In a real scenario, you'd analyze query logs or use performance schema
        
        $commonPatterns = [
            'users' => ['email', 'role', 'is_active'],
            'orders' => ['user_id', 'status', 'expires_at'],
            'payments' => ['order_id', 'status', 'payment_method'],
            'blog_posts' => ['is_published', 'published_at', 'slug'],
        ];

        $recommendations = [];
        
        foreach ($commonPatterns as $table => $columns) {
            if (Schema::hasTable($table)) {
                foreach ($columns as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        // Check if index exists (simplified)
                        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Column_name = ?", [$column]);
                        if (empty($indexes)) {
                            $recommendations[] = [
                                'table' => $table,
                                'column' => $column,
                                'recommendation' => "Consider adding index on {$table}.{$column}",
                            ];
                        }
                    }
                }
            }
        }

        return $recommendations;
    }

    /**
     * Generate optimization report
     */
    public static function generateOptimizationReport(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'database_size' => self::getDatabaseSize(),
            'table_statistics' => self::getTableStatistics(),
            'connection_status' => self::getConnectionStatus(),
            'missing_indexes' => self::checkMissingIndexes(),
            'slow_queries' => self::getSlowQueries(),
        ];
    }
}
