<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\RenewalNotification;
use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class RenewalReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_renewal_reminder_sends_exactly_three_emails_per_order()
    {
        Mail::fake();

        // Set reminder days to 7,3,0
        SystemSetting::set('renewal_reminder_days', '7,3,0');

        // Create test data
        $user = User::factory()->create(['role' => 'client']);
        
        // Create pricing plan manually
        $plan = PricingPlan::create([
            'name' => 'Test Plan',
            'server_type' => 'basic',
            'device_count' => 1,
            'duration_months' => 1,
            'price' => 10.00,
            'features' => ['Test feature'],
            'is_active' => true,
        ]);
        
        // Create order expiring in 7 days
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $plan->id,
            'order_number' => 'ORD-TEST-001',
            'status' => 'active',
            'amount' => 10.00,
            'currency' => 'USD',
            'expires_at' => now()->addDays(7),
            'order_type' => 'subscription',
            'subscription_type' => 'new',
        ]);

        // Test 1: Send 7-day reminder
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should send 1 email (7-day reminder)
        Mail::assertSentCount(1);
        Mail::assertSent(function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                   str_contains($mail->subject, 'expires in 7 days');
        });

        // Verify notification was recorded
        $this->assertDatabaseHas('renewal_notifications', [
            'order_id' => $order->id,
            'days_before_expiry' => 7,
            'sent' => true,
        ]);

        // Test 2: Run command again - should NOT send duplicate
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should still be only 1 email (no duplicate)
        Mail::assertSentCount(1);

        // Test 3: Update order to expire in 3 days and send 3-day reminder
        $order->update(['expires_at' => now()->addDays(3)]);
        
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should now have 2 emails total (7-day + 3-day)
        Mail::assertSentCount(2);
        Mail::assertSent(function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                   str_contains($mail->subject, 'expires in 3 days');
        });

        // Test 4: Update order to expire today and send expired reminder
        $order->update(['expires_at' => now()->toDateString()]);
        
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should now have 3 emails total (7-day + 3-day + expired)
        Mail::assertSentCount(3);
        Mail::assertSent(function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                   str_contains($mail->subject, 'Service Expired');
        });

        // Test 5: Run command again - should NOT send any more emails
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should still be only 3 emails (no more)
        Mail::assertSentCount(3);

        // Verify all notifications were recorded
        $this->assertDatabaseHas('renewal_notifications', [
            'order_id' => $order->id,
            'days_before_expiry' => 7,
            'sent' => true,
        ]);
        $this->assertDatabaseHas('renewal_notifications', [
            'order_id' => $order->id,
            'days_before_expiry' => 3,
            'sent' => true,
        ]);
        $this->assertDatabaseHas('renewal_notifications', [
            'order_id' => $order->id,
            'days_before_expiry' => 0,
            'sent' => true,
        ]);
    }

    public function test_multiple_orders_get_correct_reminders()
    {
        Mail::fake();

        // Set reminder days to 7,3,0
        SystemSetting::set('renewal_reminder_days', '7,3,0');

        // Create multiple orders
        $user1 = User::factory()->create(['role' => 'client']);
        $user2 = User::factory()->create(['role' => 'client']);
        
        // Create pricing plan
        $plan = PricingPlan::create([
            'name' => 'Test Plan',
            'server_type' => 'basic',
            'device_count' => 1,
            'duration_months' => 1,
            'price' => 10.00,
            'features' => ['Test feature'],
            'is_active' => true,
        ]);
        
        // Order 1: expires in 7 days
        $order1 = Order::create([
            'user_id' => $user1->id,
            'pricing_plan_id' => $plan->id,
            'order_number' => 'ORD-TEST-001',
            'status' => 'active',
            'amount' => 10.00,
            'currency' => 'USD',
            'expires_at' => now()->addDays(7),
            'order_type' => 'subscription',
            'subscription_type' => 'new',
        ]);

        // Order 2: expires in 3 days
        $order2 = Order::create([
            'user_id' => $user2->id,
            'pricing_plan_id' => $plan->id,
            'order_number' => 'ORD-TEST-002',
            'status' => 'active',
            'amount' => 10.00,
            'currency' => 'USD',
            'expires_at' => now()->addDays(3),
            'order_type' => 'subscription',
            'subscription_type' => 'new',
        ]);

        // Run the command
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should send 2 emails (one 7-day, one 3-day)
        Mail::assertSentCount(2);

        // Verify both notifications were recorded
        $this->assertDatabaseHas('renewal_notifications', [
            'order_id' => $order1->id,
            'days_before_expiry' => 7,
            'sent' => true,
        ]);
        $this->assertDatabaseHas('renewal_notifications', [
            'order_id' => $order2->id,
            'days_before_expiry' => 3,
            'sent' => true,
        ]);
    }

    public function test_custom_reminder_days_configuration()
    {
        Mail::fake();

        // Set custom reminder days
        SystemSetting::set('renewal_reminder_days', '14,7,1');

        // Create test data
        $user = User::factory()->create(['role' => 'client']);
        
        // Create pricing plan
        $plan = PricingPlan::create([
            'name' => 'Test Plan',
            'server_type' => 'basic',
            'device_count' => 1,
            'duration_months' => 1,
            'price' => 10.00,
            'features' => ['Test feature'],
            'is_active' => true,
        ]);
        
        // Create order expiring in 14 days
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $plan->id,
            'order_number' => 'ORD-TEST-001',
            'status' => 'active',
            'amount' => 10.00,
            'currency' => 'USD',
            'expires_at' => now()->addDays(14),
            'order_type' => 'subscription',
            'subscription_type' => 'new',
        ]);

        // Run the command
        $this->artisan('iptv:send-renewal-reminders')
             ->assertExitCode(0);

        // Should send 1 email (14-day reminder)
        Mail::assertSentCount(1);
        Mail::assertSent(function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                   str_contains($mail->subject, 'expires in 14 days');
        });
    }
}