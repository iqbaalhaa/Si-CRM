<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================
        // 1. Definisikan Permissions
        // ==========================

        $permissions = [
            // Roles & companies
            'manage roles',          // CRUD role
            'manage companies',      // CRUD company

            // Team & pipeline
            'manage team accounts',  // CRUD akun team (user dalam 1 company)
            'manage pipelines',      // CRUD pipeline

            // Customers (lebih detail CRU+D)
            'view customers',
            'create customers',
            'update customers',
            'delete customers',

            // CRM & reports
            'view crm',
            'manage reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ==========================
        // 2. Definisikan Roles
        // ==========================

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $marketing = Role::firstOrCreate(['name' => 'marketing']);
        $cs = Role::firstOrCreate(['name' => 'cs']);

        // ==========================
        // 3. Assign Permissions per Role
        // ==========================

        // SUPER ADMIN: full akses
        $superAdmin->syncPermissions(Permission::all());

        // ADMIN:

        $admin->syncPermissions([
            'manage team accounts',
            'manage pipelines',

            'view customers',
            'create customers',
            'update customers',
            'delete customers',

            'view crm',
            'manage reports',
        ]);

        // MARKETING:

        $marketing->syncPermissions([
            'view customers',
            'create customers',
            'update customers',

            'view crm',
            'manage reports',
        ]);

        // CS:

        $cs->syncPermissions([
            'view customers',
            'update customers',

            'view crm',
            'manage reports',
        ]);
    }
}
