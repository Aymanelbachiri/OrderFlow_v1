<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PricingPlan;
use App\Models\Order;
use App\Models\Payment;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test pricing plan
        $this->pricingPlan = PricingPlan::create([
            'name' => 'Test Plan - 1 Device - 1 Month',
            'server_type' => 'basic',
            'device_count' => 1,
            'duration_months' => 1,
            'price' => 10.00,
            'features' => ['HD Quality', '24/7 Support'],
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_view_orders_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function client_can_create_order()
    {
        $client = User::factory()->create(['role' => 'client']);
        
        $response = $this->actingAs($client)
            ->post(route('client.orders.store'), [
                'pricing_plan_id' => $this->pricingPlan->id,
                'payment_method' => 'email_link',
            ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $client->id,
            'pricing_plan_id' => $this->pricingPlan->id,
            'payment_method' => 'email_link',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function order_has_correct_amount_and_duration()
    {
        $client = User::factory()->create(['role' => 'client']);
        
        $this->actingAs($client)
            ->post(route('client.orders.store'), [
                'pricing_plan_id' => $this->pricingPlan->id,
                'payment_method' => 'email_link',
            ]);
        
        $order = Order::where('user_id', $client->id)->first();
        
        $this->assertEquals($this->pricingPlan->price, $order->amount);
        $this->assertEquals(
            now()->addMonths($this->pricingPlan->duration_months)->toDateString(),
            $order->expires_at->toDateString()
        );
    }

    /** @test */
    public function payment_success_activates_order()
    {
        $client = User::factory()->create(['role' => 'client']);
        
        $order = Order::create([
            'user_id' => $client->id,
            'pricing_plan_id' => $this->pricingPlan->id,
            'order_number' => 'TEST-001',
            'amount' => $this->pricingPlan->price,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonths($this->pricingPlan->duration_months),
        ]);
        
        $response = $this->actingAs($client)
            ->post(route('client.orders.payment.success', $order), [
                'payment_id' => 'test_payment_123',
                'payment_method' => 'email_link',
            ]);
        
        $response->assertRedirect(route('client.orders.show', $order));
        
        $order->refresh();
        $this->assertEquals('active', $order->status);
        $this->assertNotNull($order->subscription_username);
        $this->assertNotNull($order->subscription_password);
    }

    /** @test */
    public function expired_orders_cannot_be_renewed_after_grace_period()
    {
        $client = User::factory()->create(['role' => 'client']);
        
        $order = Order::create([
            'user_id' => $client->id,
            'pricing_plan_id' => $this->pricingPlan->id,
            'order_number' => 'TEST-002',
            'amount' => $this->pricingPlan->price,
            'payment_method' => 'email_link',
            'status' => 'expired',
            'expires_at' => now()->subDays(31), // Expired more than 30 days ago
        ]);
        
        $response = $this->actingAs($client)
            ->get(route('client.orders.renew', $order));
        
        // Should redirect or show error since order is too old to renew
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_manage_pricing_plans()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)
            ->post(route('admin.pricing.store'), [
                'name' => 'Premium - 2 Devices - 3 Months',
                'server_type' => 'premium',
                'device_count' => 2,
                'duration_months' => 3,
                'price' => 45.00,
                'features' => ['4K Quality', 'Premium Support'],
                'is_active' => true,
            ]);
        
        $response->assertRedirect(route('admin.pricing.index'));
        
        $this->assertDatabaseHas('pricing_plans', [
            'name' => 'Premium - 2 Devices - 3 Months',
            'server_type' => 'premium',
            'device_count' => 2,
            'duration_months' => 3,
            'price' => 45.00,
            'is_active' => true,
        ]);
    }



    /** @test */
    public function order_generates_unique_credentials()
    {
        $client = User::factory()->create(['role' => 'client']);
        
        $order1 = Order::create([
            'user_id' => $client->id,
            'pricing_plan_id' => $this->pricingPlan->id,
            'order_number' => 'TEST-003',
            'amount' => $this->pricingPlan->price,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonths($this->pricingPlan->duration_months),
        ]);
        
        $order2 = Order::create([
            'user_id' => $client->id,
            'pricing_plan_id' => $this->pricingPlan->id,
            'order_number' => 'TEST-004',
            'amount' => $this->pricingPlan->price,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonths($this->pricingPlan->duration_months),
        ]);
        
        // Activate both orders
        $order1->update(['status' => 'active']);
        $order2->update(['status' => 'active']);
        
        // Refresh to get generated credentials
        $order1->refresh();
        $order2->refresh();
        
        // Credentials should be unique
        $this->assertNotEquals($order1->subscription_username, $order2->subscription_username);
        $this->assertNotEquals($order1->subscription_password, $order2->subscription_password);
    }
}
