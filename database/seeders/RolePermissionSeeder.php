<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'view crm',              // akses dashboard & halaman utama CRM
            'manage reports',        // CRUD report
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ==========================
        // 2. Definisikan Roles
        // ==========================

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $marketing  = Role::firstOrCreate(['name' => 'marketing']);
        $cs         = Role::firstOrCreate(['name' => 'cs']);

        // ==========================
        // 3. Assign Permissions per Role
        // ==========================

        // SUPER ADMIN: full akses
        $superAdmin->syncPermissions(Permission::all());

        // ADMIN:
        // - CRUD akun team
        // - CRUD pipeline
        // - CRU customer
        // - read CRM
        // - CRUD report
        $admin->syncPermissions([
            'manage team accounts',
            'manage pipelines',

            'view customers',
            'create customers',
            'update customers',
            // (hapus 'delete customers' kalau admin tidak boleh hapus)

            'view crm',
            'manage reports',
        ]);

        // MARKETING:
        // - CRU customer
        // - read CRM
        // - CRUD report
        $marketing->syncPermissions([
            'view customers',
            'create customers',
            'update customers',

            'view crm',
            'manage reports',
        ]);

        // CS:
        // - read CRM
        // - CRUD report
        // (bisa juga dikasih view customers kalau mau)
        $cs->syncPermissions([
            'view customers',
            'view crm',
            'manage reports',
        ]);
    }
}