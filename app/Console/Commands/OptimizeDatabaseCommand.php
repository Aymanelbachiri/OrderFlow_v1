<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseOptimizationService;
use App\Services\CacheService;

class OptimizeDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize {--cleanup : Clean up old data} {--analyze : Analyze tables} {--report : Generate optimization report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance and clean up old data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting database optimization...');
        $this->newLine();

        if ($this->option('cleanup')) {
            $this->performCleanup();
        }

        if ($this->option('analyze')) {
            $this->performAnalysis();
        }

        if ($this->option('report')) {
            $this->generateReport();
        }

        if (!$this->option('cleanup') && !$this->option('analyze') && !$this->option('report')) {
            // Run all optimizations by default
            $this->performOptimization();
            $this->performCleanup();
            $this->performAnalysis();
        }

        $this->newLine();
        $this->info('✅ Database optimization completed!');

        return 0;
    }

    /**
     * Perform table optimization
     */
    private function performOptimization(): void
    {
        $this->info('🔧 Optimizing database tables...');
        
        $results = DatabaseOptimizationService::optimizeTables();
        
        foreach ($results as $table => $result) {
            if ($result === 'optimized') {
                $this->line("  ✅ {$table}: optimized");
            } else {
                $this->error("  ❌ {$table}: {$result}");
            }
        }
    }

    /**
     * Perform cleanup operations
     */
    private function performCleanup(): void
    {
        $this->info('🧹 Cleaning up old data...');
        
        $results = DatabaseOptimizationService::cleanupOldData();
        
        foreach ($results as $operation => $count) {
            if ($operation === 'error') {
                $this->error("  ❌ Error: {$count}");
            } else {
                $this->line("  ✅ {$operation}: {$count} records cleaned");
            }
        }

        // Clear application cache
        $this->info('🗑️ Clearing application cache...');
        CacheService::clearAllCache();
        $this->line('  ✅ Cache cleared');
    }

    /**
     * Perform table analysis
     */
    private function performAnalysis(): void
    {
        $this->info('📊 Analyzing database tables...');
        
        $results = DatabaseOptimizationService::analyzeTables();
        
        foreach ($results as $table => $result) {
            if ($result === 'analyzed') {
                $this->line("  ✅ {$table}: analyzed");
            } else {
                $this->error("  ❌ {$table}: {$result}");
            }
        }

        // Check for missing indexes
        $this->info('🔍 Checking for optimization opportunities...');
        $recommendations = DatabaseOptimizationService::checkMissingIndexes();
        
        if (empty($recommendations)) {
            $this->line('  ✅ No obvious optimization opportunities found');
        } else {
            foreach ($recommendations as $type => $items) {
                $this->warn("  ⚠️ {$type}:");
                foreach ($items as $item) {
                    if (is_array($item)) {
                        $this->line("    - {$item['recommendation']}");
                    } else {
                        $this->line("    - {$item}");
                    }
                }
            }
        }
    }

    /**
     * Generate optimization report
     */
    private function generateReport(): void
    {
        $this->info('📋 Generating optimization report...');
        
        $report = DatabaseOptimizationService::generateOptimizationReport();
        
        // Display database size
        $this->newLine();
        $this->info('💾 Database Size Information:');
        $this->line("  Total Size: {$report['database_size']['total_size_mb']} MB");
        
        $this->newLine();
        $this->info('📊 Largest Tables:');
        $topTables = array_slice($report['database_size']['tables'], 0, 5);
        foreach ($topTables as $table) {
            $this->line("  {$table->table_name}: {$table->size_mb} MB ({$table->table_rows} rows)");
        }

        // Display connection status
        if (isset($report['connection_status']['Threads_connected'])) {
            $this->newLine();
            $this->info('🔗 Connection Status:');
            $this->line("  Active Connections: {$report['connection_status']['Threads_connected']}");
            $this->line("  Max Connections: {$report['connection_status']['max_connections']}");
        }

        // Display slow queries if any
        if (!empty($report['slow_queries'])) {
            $this->newLine();
            $this->warn('🐌 Slow Queries Found:');
            foreach (array_slice($report['slow_queries'], 0, 3) as $query) {
                $this->line("  - Avg Time: {$query->avg_time_seconds}s, Executions: {$query->exec_count}");
            }
        }

        // Save report to file
        $reportFile = storage_path('logs/database_optimization_' . now()->format('Y-m-d_H-i-s') . '.json');
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        
        $this->newLine();
        $this->info("📄 Full report saved to: {$reportFile}");
    }
}
