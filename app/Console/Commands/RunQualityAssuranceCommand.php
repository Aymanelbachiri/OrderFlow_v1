<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\SecurityService;
use App\Services\PerformanceMonitoringService;

class RunQualityAssuranceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:run {--security : Run security tests only} {--performance : Run performance tests only} {--coverage : Generate code coverage report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run comprehensive quality assurance tests including security, performance, and functionality tests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Quality Assurance Tests...');
        $this->newLine();

        $startTime = microtime(true);
        $results = [];

        if ($this->option('security')) {
            $results['security'] = $this->runSecurityTests();
        } elseif ($this->option('performance')) {
            $results['performance'] = $this->runPerformanceTests();
        } else {
            // Run all tests
            $results['functionality'] = $this->runFunctionalityTests();
            $results['security'] = $this->runSecurityTests();
            $results['performance'] = $this->runPerformanceTests();
            $results['api'] = $this->runApiTests();
        }

        if ($this->option('coverage')) {
            $this->generateCoverageReport();
        }

        $endTime = microtime(true);
        $totalTime = round($endTime - $startTime, 2);

        $this->displayResults($results, $totalTime);

        return $this->determineExitCode($results);
    }

    /**
     * Run functionality tests
     */
    private function runFunctionalityTests(): array
    {
        $this->info('🧪 Running Functionality Tests...');
        
        $exitCode = Artisan::call('test', [
            '--testsuite' => 'Feature',
            '--exclude-group' => 'security,performance,api',
        ]);

        return [
            'status' => $exitCode === 0 ? 'passed' : 'failed',
            'exit_code' => $exitCode,
            'output' => Artisan::output(),
        ];
    }

    /**
     * Run security tests
     */
    private function runSecurityTests(): array
    {
        $this->info('🔒 Running Security Tests...');
        
        $results = [];
        
        // Run security test suite
        $exitCode = Artisan::call('test', [
            'tests/Feature/SecurityTest.php',
        ]);
        
        $results['test_suite'] = [
            'status' => $exitCode === 0 ? 'passed' : 'failed',
            'exit_code' => $exitCode,
        ];

        // Run security audit
        $this->line('  Running security audit...');
        $auditExitCode = Artisan::call('security:audit');
        
        $results['security_audit'] = [
            'status' => $auditExitCode === 0 ? 'passed' : 'failed',
            'exit_code' => $auditExitCode,
        ];

        // Check system health
        $this->line('  Checking system health...');
        $health = PerformanceMonitoringService::checkSystemHealth();
        
        $results['system_health'] = [
            'status' => $health['status'],
            'score' => $health['score'],
            'issues' => $health['issues'],
        ];

        return $results;
    }

    /**
     * Run performance tests
     */
    private function runPerformanceTests(): array
    {
        $this->info('⚡ Running Performance Tests...');
        
        $results = [];
        
        // Run performance test suite
        $exitCode = Artisan::call('test', [
            'tests/Feature/PerformanceTest.php',
        ]);
        
        $results['test_suite'] = [
            'status' => $exitCode === 0 ? 'passed' : 'failed',
            'exit_code' => $exitCode,
        ];

        // Generate performance report
        $this->line('  Generating performance report...');
        $performanceReport = PerformanceMonitoringService::generatePerformanceReport();
        
        $results['performance_report'] = $performanceReport;

        // Check memory usage
        $memoryUsage = PerformanceMonitoringService::getMemoryUsage();
        $results['memory_check'] = [
            'status' => $memoryUsage['current'] < (100 * 1024 * 1024) ? 'passed' : 'warning',
            'current_mb' => round($memoryUsage['current'] / 1024 / 1024, 2),
            'peak_mb' => round($memoryUsage['peak'] / 1024 / 1024, 2),
        ];

        return $results;
    }

    /**
     * Run API tests
     */
    private function runApiTests(): array
    {
        $this->info('🌐 Running API Tests...');
        
        $exitCode = Artisan::call('test', [
            'tests/Feature/ApiTest.php',
        ]);

        return [
            'status' => $exitCode === 0 ? 'passed' : 'failed',
            'exit_code' => $exitCode,
            'output' => Artisan::output(),
        ];
    }

    /**
     * Generate code coverage report
     */
    private function generateCoverageReport(): void
    {
        $this->info('📊 Generating Code Coverage Report...');
        
        $exitCode = Artisan::call('test', [
            '--coverage-html' => 'coverage-report',
            '--coverage-clover' => 'coverage.xml',
        ]);

        if ($exitCode === 0) {
            $this->line('  ✅ Coverage report generated in coverage-report/ directory');
        } else {
            $this->error('  ❌ Failed to generate coverage report');
        }
    }

    /**
     * Display test results
     */
    private function displayResults(array $results, float $totalTime): void
    {
        $this->newLine();
        $this->info('📋 Quality Assurance Results:');
        $this->line("Total execution time: {$totalTime} seconds");
        $this->newLine();

        foreach ($results as $category => $result) {
            $this->displayCategoryResults($category, $result);
        }

        // Overall summary
        $this->newLine();
        $this->info('📊 Overall Summary:');
        
        $totalTests = count($results);
        $passedTests = 0;
        
        foreach ($results as $result) {
            if (is_array($result) && isset($result['status']) && $result['status'] === 'passed') {
                $passedTests++;
            } elseif (is_array($result) && isset($result['test_suite']['status']) && $result['test_suite']['status'] === 'passed') {
                $passedTests++;
            }
        }
        
        $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;
        
        if ($successRate >= 90) {
            $this->info("✅ Success Rate: {$successRate}% - Excellent!");
        } elseif ($successRate >= 75) {
            $this->warn("⚠️ Success Rate: {$successRate}% - Good, but needs improvement");
        } else {
            $this->error("❌ Success Rate: {$successRate}% - Needs significant improvement");
        }
    }

    /**
     * Display results for a specific category
     */
    private function displayCategoryResults(string $category, array $result): void
    {
        $categoryName = ucfirst(str_replace('_', ' ', $category));
        
        if (isset($result['status'])) {
            $status = $result['status'] === 'passed' ? '✅' : '❌';
            $this->line("{$status} {$categoryName}: {$result['status']}");
        } elseif (isset($result['test_suite'])) {
            $status = $result['test_suite']['status'] === 'passed' ? '✅' : '❌';
            $this->line("{$status} {$categoryName} Tests: {$result['test_suite']['status']}");
            
            // Display additional information
            if (isset($result['security_audit'])) {
                $auditStatus = $result['security_audit']['status'] === 'passed' ? '✅' : '❌';
                $this->line("  {$auditStatus} Security Audit: {$result['security_audit']['status']}");
            }
            
            if (isset($result['system_health'])) {
                $healthStatus = $result['system_health']['status'] === 'healthy' ? '✅' : '⚠️';
                $this->line("  {$healthStatus} System Health: {$result['system_health']['status']} (Score: {$result['system_health']['score']}/100)");
                
                if (!empty($result['system_health']['issues'])) {
                    foreach ($result['system_health']['issues'] as $issue) {
                        $this->line("    - {$issue}");
                    }
                }
            }
            
            if (isset($result['memory_check'])) {
                $memoryStatus = $result['memory_check']['status'] === 'passed' ? '✅' : '⚠️';
                $this->line("  {$memoryStatus} Memory Usage: {$result['memory_check']['current_mb']} MB current, {$result['memory_check']['peak_mb']} MB peak");
            }
        }
    }

    /**
     * Determine exit code based on results
     */
    private function determineExitCode(array $results): int
    {
        foreach ($results as $result) {
            if (is_array($result)) {
                if (isset($result['exit_code']) && $result['exit_code'] !== 0) {
                    return 1;
                }
                if (isset($result['test_suite']['exit_code']) && $result['test_suite']['exit_code'] !== 0) {
                    return 1;
                }
                if (isset($result['status']) && $result['status'] === 'failed') {
                    return 1;
                }
            }
        }
        
        return 0;
    }
}
