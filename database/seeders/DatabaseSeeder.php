<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolePermissionSeeder::class,
            DefaultDataSeeder::class,
            SmtpSettingSeeder::class,
            UserSeeder::class,
        ]);

        // // Create default admin user
        // $admin = User::factory()->create([
        //     'name' => 'Smarters',
        //     'email' => 'contact@smarters-proiptv.com',
        //     'role' => 'admin',
        //     'email_verified_at' => now(),
        // ]);

        // $admin->assignRole('admin');
    }
}
