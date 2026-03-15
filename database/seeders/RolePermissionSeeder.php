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
        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $resellerRole = Role::firstOrCreate(['name' => 'reseller', 'guard_name' => 'web']);
        $agentRole = Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);

        // Assign permissions
        $adminRole->syncPermissions(Permission::all()); // replaces givePermissionTo()
        
        // Assign client permissions
        $clientPermissions = Permission::whereIn('name', [
            'view-own-orders',
            'manage-own-profile',
            'renew-subscription',
        ])->get();
        $clientRole->syncPermissions($clientPermissions);
        
        // Assign reseller permissions
        $resellerPermissions = Permission::whereIn('name', [
            'view-reseller-dashboard',
            'manage-reseller-orders',
            'view-reseller-revenue',
        ])->get();
        $resellerRole->syncPermissions($resellerPermissions);

        // Assign agent permissions (source-scoped subset of admin)
        $agentPermissions = Permission::whereIn('name', [
            'manage-orders',
            'manage-users',
            'manage-resellers',
            'view-analytics',
        ])->get();
        $agentRole->syncPermissions($agentPermissions);
    }
}
