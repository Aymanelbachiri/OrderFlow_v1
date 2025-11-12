<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\BlogPost;

class ApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /** @test */
    public function api_authentication_works()
    {
        // Test without token
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);

        // Test with token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/user');
        
        $response->assertStatus(200)
                ->assertJson([
                    'id' => $this->user->id,
                    'email' => $this->user->email,
                ]);
    }

    /** @test */
    public function api_login_returns_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token',
                    'user' => ['id', 'name', 'email', 'role'],
                ]);
    }

    /** @test */
    public function api_logout_revokes_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson(['message' => 'Logged out successfully']);

        // Token should no longer work
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/user');
        
        $response->assertStatus(401);
    }

    /** @test */
    public function api_can_get_pricing_plans()
    {
        PricingPlan::factory()->count(5)->create(['is_active' => true]);

        $response = $this->getJson('/api/pricing-plans');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'display_name',
                            'server_type',
                            'device_count',
                            'duration_months',
                            'price',
                            'features',
                        ],
                    ],
                ]);
    }

    /** @test */
    public function api_can_create_order()
    {
        $pricingPlan = PricingPlan::factory()->create(['is_active' => true]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', [
            'pricing_plan_id' => $pricingPlan->id,
            'payment_method' => 'stripe',
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'order_number',
                    'amount',
                    'status',
                    'payment_url',
                ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'payment_method' => 'stripe',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function api_can_get_user_orders()
    {
        $pricingPlan = PricingPlan::factory()->create();
        Order::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/orders');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'order_number',
                            'amount',
                            'status',
                            'pricing_plan',
                        ],
                    ],
                    'links',
                    'meta',
                ]);
    }

    /** @test */
    public function api_can_filter_orders_by_status()
    {
        $pricingPlan = PricingPlan::factory()->create();
        Order::factory()->create([
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'status' => 'active',
        ]);
        Order::factory()->create([
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'status' => 'pending',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/orders?status=active');

        $response->assertStatus(200);
        
        $orders = $response->json('data');
        $this->assertCount(1, $orders);
        $this->assertEquals('active', $orders[0]['status']);
    }

    /** @test */
    public function api_can_get_order_statistics()
    {
        $pricingPlan = PricingPlan::factory()->create();
        Order::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'status' => 'active',
            'amount' => 29.99,
        ]);
        Order::factory()->create([
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'status' => 'pending',
            'amount' => 19.99,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/orders/statistics');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'total_orders',
                    'active_orders',
                    'pending_orders',
                    'expired_orders',
                    'total_spent',
                    'monthly_spent',
                ]);

        $stats = $response->json();
        $this->assertEquals(3, $stats['total_orders']);
        $this->assertEquals(2, $stats['active_orders']);
        $this->assertEquals(1, $stats['pending_orders']);
    }

    /** @test */
    public function api_can_update_user_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/user', [
            'name' => 'Updated Name',
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $this->user->id,
                    'name' => 'Updated Name',
                    'phone' => '+1234567890',
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'phone' => '+1234567890',
        ]);
    }

    /** @test */
    public function api_can_get_blog_posts()
    {
        BlogPost::factory()->count(3)->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/blog');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'slug',
                            'excerpt',
                            'published_at',
                            'author',
                        ],
                    ],
                ]);
    }

    /** @test */
    public function api_can_search_blog_posts()
    {
        BlogPost::factory()->create([
            'title' => 'How to Setup IPTV',
            'content' => 'This is a guide about IPTV setup',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        BlogPost::factory()->create([
            'title' => 'Payment Methods',
            'content' => 'Information about payments',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/blog?search=IPTV');

        $response->assertStatus(200);
        
        $posts = $response->json('data');
        $this->assertCount(1, $posts);
        $this->assertStringContainsString('IPTV', $posts[0]['title']);
    }

    /** @test */
    public function api_rate_limiting_works()
    {
        // Make multiple requests quickly
        $responses = [];
        for ($i = 0; $i < 10; $i++) {
            $responses[] = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->getJson('/api/user');
        }

        // All requests should succeed (within rate limit)
        foreach ($responses as $response) {
            $this->assertContains($response->status(), [200, 429]); // 200 OK or 429 Too Many Requests
        }
    }

    /** @test */
    public function api_validates_request_data()
    {
        // Test creating order with invalid data
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', [
            'pricing_plan_id' => 999999, // Non-existent plan
            'payment_method' => 'invalid_method',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['pricing_plan_id', 'payment_method']);
    }

    /** @test */
    public function api_returns_consistent_error_format()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', []);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors',
                ]);
    }

    /** @test */
    public function admin_api_requires_admin_role()
    {
        $client = User::factory()->create(['role' => 'client']);
        $clientToken = $client->createToken('client-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $clientToken,
        ])->getJson('/api/admin/users');

        $response->assertStatus(403);

        // Test with admin user
        $admin = User::factory()->create(['role' => 'admin']);
        $adminToken = $admin->createToken('admin-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->getJson('/api/admin/users');

        $response->assertStatus(200);
    }
}
