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
        //
         User::create([
            'name' => 'Smarters Pro',
            'email' => 'contact@smarters-proiptv.com',
            'password' => Hash::make('Dofus2@0@!'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
