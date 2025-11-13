<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate clients from users table to clients table
        $clients = \DB::table('users')
            ->whereIn('role', ['client', 'reseller'])
            ->get();

        foreach ($clients as $user) {
            $clientId = \DB::table('clients')->insertGetId([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'password' => $user->password,
                'type' => $user->role, // 'client' or 'reseller'
                'is_active' => $user->is_active,
                'suspended_at' => $user->suspended_at,
                'suspension_reason' => $user->suspension_reason,
                'email_verified_at' => $user->email_verified_at,
                'stripe_id' => $user->stripe_id,
                'pm_type' => $user->pm_type,
                'pm_last_four' => $user->pm_last_four,
                'trial_ends_at' => $user->trial_ends_at,
                'reseller_panel_url' => $user->reseller_panel_url,
                'reseller_panel_username' => $user->reseller_panel_username,
                'reseller_panel_password' => $user->reseller_panel_password,
                'available_credits' => $user->available_credits ?? 0,
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);

            // Update orders to reference the new client_id
            \DB::table('orders')
                ->where('user_id', $user->id)
                ->update(['client_id' => $clientId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be fully reversed as we don't know which users were clients
        // and which were admins. The data would need to be manually restored.
        \DB::table('orders')->update(['client_id' => null]);
    }
};
