<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\PricingPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_belongs_to_user()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'TEST-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonth(),
        ]);
        
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /** @test */
    public function order_belongs_to_pricing_plan()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'TEST-002',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonth(),
        ]);
        
        $this->assertInstanceOf(PricingPlan::class, $order->pricingPlan);
        $this->assertEquals($pricingPlan->id, $order->pricingPlan->id);
    }

    /** @test */
    public function order_can_check_if_active()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create();
        
        $activeOrder = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'ACTIVE-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'active',
            'expires_at' => now()->addMonth(),
        ]);
        
        $expiredOrder = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'EXPIRED-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'expired',
            'expires_at' => now()->subMonth(),
        ]);
        
        $this->assertTrue($activeOrder->isActive());
        $this->assertFalse($expiredOrder->isActive());
    }

    /** @test */
    public function order_can_calculate_days_until_expiry()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'DAYS-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'active',
            'expires_at' => now()->addDays(15),
        ]);
        
        $this->assertEquals(15, $order->daysUntilExpiry());
        
        // Test expired order
        $expiredOrder = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'EXPIRED-002',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'expired',
            'expires_at' => now()->subDays(5),
        ]);
        
        $this->assertEquals(-5, $expiredOrder->daysUntilExpiry());
    }

    /** @test */
    public function order_generates_credentials_when_activated()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'CRED-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonth(),
        ]);
        
        // Initially no credentials
        $this->assertNull($order->subscription_username);
        $this->assertNull($order->subscription_password);
        
        // Activate order
        $order->update(['status' => 'active']);
        $order->refresh();
        
        // Should now have credentials
        $this->assertNotNull($order->subscription_username);
        $this->assertNotNull($order->subscription_password);
        $this->assertNotNull($order->subscription_url);
    }

    /** @test */
    public function order_number_is_unique()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create();
        
        $order1 = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'UNIQUE-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonth(),
        ]);
        
        // Try to create another order with same order number
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'UNIQUE-001', // Same as above
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'pending',
            'expires_at' => now()->addMonth(),
        ]);
    }

    /** @test */
    public function order_has_display_name_attribute()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create([
            'name' => 'Premium - 2 Devices - 3 Months',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'DISPLAY-001',
            'amount' => 45.00,
            'payment_method' => 'email_link',
            'status' => 'active',
            'expires_at' => now()->addMonths(3),
        ]);
        
        $this->assertEquals('Premium - 2 Devices - 3 Months', $order->pricingPlan->display_name);
    }

    /** @test */
    public function order_can_be_renewed()
    {
        $user = User::factory()->create();
        $pricingPlan = PricingPlan::factory()->create(['duration_months' => 1]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'order_number' => 'RENEW-001',
            'amount' => 10.00,
            'payment_method' => 'email_link',
            'status' => 'active',
            'expires_at' => now()->addDays(5), // Expiring soon
        ]);
        
        $originalExpiry = $order->expires_at;
        
        // Simulate renewal by extending expiry
        $order->update([
            'expires_at' => $order->expires_at->addMonths($pricingPlan->duration_months)
        ]);
        
        $this->assertTrue($order->expires_at->gt($originalExpiry));
        $this->assertEquals(
            $originalExpiry->addMonths(1)->toDateString(),
            $order->expires_at->toDateString()
        );
    }
}
