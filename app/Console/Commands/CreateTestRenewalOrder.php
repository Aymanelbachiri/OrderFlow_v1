<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\PricingPlan;

class CreateTestRenewalOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iptv:create-test-renewal-order {email=ayman.dofus@gmail.com} {--days=0 : Days until expiry (0 = today)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test order for renewal reminder testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $daysUntilExpiry = (int) $this->option('days');

        $this->info("Creating test order for: {$email}");

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'client'
            ]
        );

        $this->info("User: {$user->email} (ID: {$user->id})");

        // Get or create a pricing plan
        $plan = PricingPlan::where('is_active', true)->first();
        if (!$plan) {
            $plan = PricingPlan::create([
                'name' => 'test-plan',
                'display_name' => 'Test Plan',
                'server_type' => 'basic',
                'device_count' => 1,
                'duration_months' => 1,
                'price' => 10.00,
                'features' => ['Test'],
                'is_active' => true,
            ]);
            $this->info("Created pricing plan: {$plan->display_name}");
        } else {
            $this->info("Using pricing plan: {$plan->display_name} (ID: {$plan->id})");
        }

        // Delete any existing test orders for this user
        $deleted = Order::where('user_id', $user->id)
            ->where('order_number', 'like', 'TEST-%')
            ->delete();
        
        if ($deleted > 0) {
            $this->info("Deleted {$deleted} existing test order(s)");
        }

        // Calculate expiry date
        if ($daysUntilExpiry === 0) {
            $expiresAt = now()->endOfDay();
        } else {
            $expiresAt = now()->addDays($daysUntilExpiry)->endOfDay();
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'pricing_plan_id' => $plan->id,
            'order_number' => 'TEST-' . strtoupper(uniqid()),
            'status' => 'active',
            'amount' => $plan->price,
            'payment_method' => 'manual',
            'order_type' => 'subscription',
            'subscription_type' => 'new',
            'expires_at' => $expiresAt,
            'starts_at' => now()->subMonth(),
        ]);

        $this->info("✓ Created order: {$order->order_number}");
        $this->info("  Order ID: {$order->id}");
        $this->info("  Expires at: {$order->expires_at->format('Y-m-d H:i:s')}");
        $this->info("  Days until expiry: " . $order->daysUntilExpiry());

        // Delete any existing renewal notifications for this order
        \App\Models\RenewalNotification::where('order_id', $order->id)->delete();
        $this->info("  Cleared any existing renewal notifications");

        return 0;
    }
}

