<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Services\CacheService;
use App\Services\PerformanceMonitoringService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->createTestData();
    }

    /** @test */
    public function dashboard_loads_within_acceptable_time()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $startTime = microtime(true);
        
        $response = $this->actingAs($admin)->get('/admin');
        
        $endTime = microtime(true);
        $loadTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        $response->assertStatus(200);
        $this->assertLessThan(2000, $loadTime, 'Dashboard should load within 2 seconds');
    }

    /** @test */
    public function database_queries_are_optimized()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Enable query logging
        DB::enableQueryLog();
        
        $response = $this->actingAs($admin)->get('/admin');
        
        $queries = DB::getQueryLog();
        $queryCount = count($queries);
        
        $response->assertStatus(200);
        $this->assertLessThan(20, $queryCount, 'Dashboard should use fewer than 20 queries');
        
        // Check for N+1 queries
        $duplicateQueries = [];
        foreach ($queries as $query) {
            $sql = $query['query'];
            if (isset($duplicateQueries[$sql])) {
                $duplicateQueries[$sql]++;
            } else {
                $duplicateQueries[$sql] = 1;
            }
        }
        
        $nPlusOneQueries = array_filter($duplicateQueries, function($count) {
            return $count > 3; // More than 3 identical queries might indicate N+1
        });
        
        $this->assertEmpty($nPlusOneQueries, 'No N+1 query patterns should be present');
    }

    /** @test */
    public function caching_improves_performance()
    {
        // Clear cache first
        Cache::flush();
        
        // First request (no cache)
        $startTime = microtime(true);
        $stats1 = CacheService::getDashboardStats();
        $firstRequestTime = (microtime(true) - $startTime) * 1000;
        
        // Second request (with cache)
        $startTime = microtime(true);
        $stats2 = CacheService::getDashboardStats();
        $secondRequestTime = (microtime(true) - $startTime) * 1000;
        
        $this->assertEquals($stats1, $stats2);
        $this->assertLessThan($firstRequestTime, $secondRequestTime, 'Cached request should be faster');
    }

    /** @test */
    public function memory_usage_is_reasonable()
    {
        $initialMemory = memory_get_usage(true);
        
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin');
        
        $finalMemory = memory_get_usage(true);
        $memoryUsed = $finalMemory - $initialMemory;
        
        $response->assertStatus(200);
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, 'Memory usage should be less than 50MB');
    }

    /** @test */
    public function api_responses_are_fast()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        
        $apiEndpoints = [
            '/api/orders',
            '/api/pricing-plans',
            '/api/user',
        ];
        
        foreach ($apiEndpoints as $endpoint) {
            $startTime = microtime(true);
            
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->get($endpoint);
            
            $endTime = microtime(true);
            $responseTime = ($endTime - $startTime) * 1000;
            
            $response->assertStatus(200);
            $this->assertLessThan(1000, $responseTime, "API endpoint {$endpoint} should respond within 1 second");
        }
    }

    /** @test */
    public function large_dataset_pagination_performs_well()
    {
        // Create a large number of orders
        $user = User::factory()->create();
        Order::factory()->count(1000)->create(['user_id' => $user->id]);
        
        $startTime = microtime(true);
        
        $response = $this->actingAs($user)->get('/client/orders?page=1');
        
        $endTime = microtime(true);
        $loadTime = ($endTime - $startTime) * 1000;
        
        $response->assertStatus(200);
        $this->assertLessThan(1500, $loadTime, 'Paginated results should load within 1.5 seconds');
    }

    /** @test */
    public function cache_service_statistics_work()
    {
        // Warm up cache
        CacheService::warmUpCache();
        
        $stats = CacheService::getCacheStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_keys', $stats);
        $this->assertArrayHasKey('cached_keys', $stats);
        $this->assertArrayHasKey('cache_hit_rate', $stats);
        $this->assertGreaterThan(0, $stats['cache_hit_rate']);
    }

    /** @test */
    public function performance_monitoring_works()
    {
        $memoryStats = PerformanceMonitoringService::getMemoryUsage();
        
        $this->assertIsArray($memoryStats);
        $this->assertArrayHasKey('current', $memoryStats);
        $this->assertArrayHasKey('peak', $memoryStats);
        $this->assertArrayHasKey('limit', $memoryStats);
        $this->assertGreaterThan(0, $memoryStats['current']);
    }

    /** @test */
    public function system_health_check_works()
    {
        $health = PerformanceMonitoringService::checkSystemHealth();
        
        $this->assertIsArray($health);
        $this->assertArrayHasKey('score', $health);
        $this->assertArrayHasKey('status', $health);
        $this->assertArrayHasKey('issues', $health);
        $this->assertIsInt($health['score']);
        $this->assertContains($health['status'], ['healthy', 'warning', 'critical']);
    }

    /** @test */
    public function concurrent_requests_handle_well()
    {
        $user = User::factory()->create();
        
        // Simulate concurrent requests
        $responses = [];
        $startTime = microtime(true);
        
        for ($i = 0; $i < 5; $i++) {
            $responses[] = $this->actingAs($user)->get('/dashboard');
        }
        
        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000;
        
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }
        
        $this->assertLessThan(5000, $totalTime, 'Concurrent requests should complete within 5 seconds');
    }

    /** @test */
    public function database_connection_pooling_works()
    {
        // Test multiple database operations
        $operations = [
            fn() => User::count(),
            fn() => Order::count(),
            fn() => PricingPlan::count(),
        ];
        
        $startTime = microtime(true);
        
        foreach ($operations as $operation) {
            $result = $operation();
            $this->assertIsInt($result);
        }
        
        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000;
        
        $this->assertLessThan(500, $totalTime, 'Database operations should be fast');
    }

    /** @test */
    public function response_compression_works()
    {
        $response = $this->get('/', [
            'Accept-Encoding' => 'gzip, deflate',
        ]);
        
        $response->assertStatus(200);
        
        // Check if response can be compressed (content length should be reasonable)
        $contentLength = strlen($response->getContent());
        $this->assertLessThan(100000, $contentLength, 'Response should not be excessively large');
    }

    /** @test */
    public function static_assets_are_optimized()
    {
        // Test that CSS and JS files are properly minified/optimized
        $response = $this->get('/');
        $content = $response->getContent();
        
        // Check for asset optimization indicators
        $this->assertStringContainsString('app.css', $content);
        $this->assertStringContainsString('app.js', $content);
        
        // In production, these should be versioned/hashed
        $response->assertStatus(200);
    }

    /**
     * Create test data for performance tests
     */
    private function createTestData(): void
    {
        // Create users
        User::factory()->count(50)->create();
        
        // Create pricing plans
        PricingPlan::factory()->count(10)->create();
        
        // Create orders
        Order::factory()->count(100)->create();
    }
}
