<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'contact@smarters-proiptv.com'],
            [
                'name' => 'Smarters Pro',
                'password' => Hash::make('Dofus2@0@!'),
                'role' => 'admin',
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Update password if user already exists
        if ($superAdmin->wasRecentlyCreated === false) {
            $superAdmin->update([
                'password' => Hash::make('Dofus2@0@!'),
                'is_super_admin' => true,
                'role' => 'admin',
            ]);
        }

        // Assign admin role
        if (!$superAdmin->hasRole('admin')) {
            $superAdmin->assignRole('admin');
        }
    }
}
