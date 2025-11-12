<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            // Admin permissions
            'manage-users',
            'manage-orders',
            'manage-payments',
            'manage-pricing',
            'manage-blog',
            'manage-email-templates',
            'manage-settings',
            'view-analytics',
            'manage-resellers',

            // Client permissions
            'view-own-orders',
            'manage-own-profile',
            'renew-subscription',

            // Reseller permissions
            'view-reseller-dashboard',
            'manage-reseller-orders',
            'view-reseller-revenue',
        ];

        // ✅ Use firstOrCreate to avoid duplicates
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // ✅ Use firstOrCreate for roles too
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        

        // Assign permissions
        $adminRole->syncPermissions(Permission::all()); // replaces givePermissionTo()

        

        
    }
}
