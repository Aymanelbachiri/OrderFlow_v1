<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\PricingPlan;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default system settings
        $settings = [
            ['key' => 'site_name', 'value' => 'IPTV Management Platform', 'type' => 'string', 'description' => 'Website name'],
            ['key' => 'smtp_host', 'value' => '', 'type' => 'string', 'description' => 'SMTP host'],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'integer', 'description' => 'SMTP port'],
            ['key' => 'smtp_username', 'value' => '', 'type' => 'string', 'description' => 'SMTP username'],
            ['key' => 'smtp_password', 'value' => '', 'type' => 'string', 'description' => 'SMTP password'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'string', 'description' => 'SMTP encryption'],
            ['key' => 'primary_payment_method', 'value' => 'email_link', 'type' => 'string', 'description' => 'Primary payment method'],
            ['key' => 'stripe_enabled', 'value' => 'false', 'type' => 'boolean', 'description' => 'Enable Stripe payments'],
            ['key' => 'paypal_enabled', 'value' => 'false', 'type' => 'boolean', 'description' => 'Enable PayPal payments'],
            ['key' => 'crypto_enabled', 'value' => 'false', 'type' => 'boolean', 'description' => 'Enable crypto payments'],
            ['key' => 'renewal_reminder_days', 'value' => '7,3,0', 'type' => 'string', 'description' => 'Days before expiry to send renewal reminders (0 = expired)'],
            ['key' => 'renewal_link_url', 'value' => '', 'type' => 'string', 'description' => 'Custom renewal link URL (leave empty to use default)'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }


        // Create pricing plans based on the pricing table
        $pricingPlans = [
            // Regular Plans (Basic)
            ['server_type' => 'basic', 'device_count' => 1, 'duration_months' => 1, 'price' => 10.00],
            ['server_type' => 'basic', 'device_count' => 1, 'duration_months' => 3, 'price' => 24.00],
            ['server_type' => 'basic', 'device_count' => 1, 'duration_months' => 6, 'price' => 35.00],
            ['server_type' => 'basic', 'device_count' => 1, 'duration_months' => 12, 'price' => 49.00],
            
            ['server_type' => 'basic', 'device_count' => 2, 'duration_months' => 1, 'price' => 16.00],
            ['server_type' => 'basic', 'device_count' => 2, 'duration_months' => 3, 'price' => 37.00],
            ['server_type' => 'basic', 'device_count' => 2, 'duration_months' => 6, 'price' => 60.00],
            ['server_type' => 'basic', 'device_count' => 2, 'duration_months' => 12, 'price' => 90.00],
            
            ['server_type' => 'basic', 'device_count' => 3, 'duration_months' => 1, 'price' => 22.00],
            ['server_type' => 'basic', 'device_count' => 3, 'duration_months' => 3, 'price' => 59.00],
            ['server_type' => 'basic', 'device_count' => 3, 'duration_months' => 6, 'price' => 90.00],
            ['server_type' => 'basic', 'device_count' => 3, 'duration_months' => 12, 'price' => 130.00],
            
            ['server_type' => 'basic', 'device_count' => 4, 'duration_months' => 1, 'price' => 30.00],
            ['server_type' => 'basic', 'device_count' => 4, 'duration_months' => 3, 'price' => 80.00],
            ['server_type' => 'basic', 'device_count' => 4, 'duration_months' => 6, 'price' => 120.00],
            ['server_type' => 'basic', 'device_count' => 4, 'duration_months' => 12, 'price' => 170.00],
            
            // Premium Plans
            ['server_type' => 'premium', 'device_count' => 1, 'duration_months' => 1, 'price' => 15.00],
            ['server_type' => 'premium', 'device_count' => 1, 'duration_months' => 3, 'price' => 29.00],
            ['server_type' => 'premium', 'device_count' => 1, 'duration_months' => 6, 'price' => 49.00],
            ['server_type' => 'premium', 'device_count' => 1, 'duration_months' => 12, 'price' => 79.00],
            
            ['server_type' => 'premium', 'device_count' => 2, 'duration_months' => 1, 'price' => 30.00],
            ['server_type' => 'premium', 'device_count' => 2, 'duration_months' => 3, 'price' => 57.00],
            ['server_type' => 'premium', 'device_count' => 2, 'duration_months' => 6, 'price' => 89.00],
            ['server_type' => 'premium', 'device_count' => 2, 'duration_months' => 12, 'price' => 149.00],
            
            ['server_type' => 'premium', 'device_count' => 3, 'duration_months' => 1, 'price' => 37.00],
            ['server_type' => 'premium', 'device_count' => 3, 'duration_months' => 3, 'price' => 85.00],
            ['server_type' => 'premium', 'device_count' => 3, 'duration_months' => 6, 'price' => 145.00],
            ['server_type' => 'premium', 'device_count' => 3, 'duration_months' => 12, 'price' => 225.00],
            
            ['server_type' => 'premium', 'device_count' => 4, 'duration_months' => 1, 'price' => 48.00],
            ['server_type' => 'premium', 'device_count' => 4, 'duration_months' => 3, 'price' => 105.00],
            ['server_type' => 'premium', 'device_count' => 4, 'duration_months' => 6, 'price' => 179.00],
            ['server_type' => 'premium', 'device_count' => 4, 'duration_months' => 12, 'price' => 299.00],
        ];

        foreach ($pricingPlans as $plan) {
            PricingPlan::updateOrCreate([
                'server_type' => $plan['server_type'],
                'device_count' => $plan['device_count'],
                'duration_months' => $plan['duration_months'],
            ], [
                'name' => ucfirst($plan['server_type']) . ' - ' . $plan['device_count'] . ' Device(s) - ' . $plan['duration_months'] . ' Month(s)',
                'plan_type' => 'regular',
                'price' => $plan['price'],
                'features' => [
                    'HD Quality Streaming',
                    '24/7 Support',
                    'Multiple Device Support',
                    $plan['server_type'] === 'premium' ? 'Premium Channels' : 'Standard Channels',
                ],
                'is_active' => true,
            ]);
        }
    }
}
