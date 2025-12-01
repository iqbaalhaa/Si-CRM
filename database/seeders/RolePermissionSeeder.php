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
            // ROLES (CRUD)
            'create roles',
            'read roles',
            'update roles',
            'delete roles',

            // COMPANIES (CRUD)
            'create companies',
            'read companies',
            'update companies',
            'delete companies',

            // TEAM ACCOUNTS (CRUD)
            'create team accounts',
            'read team accounts',
            'update team accounts',
            'delete team accounts',

            // PIPELINES (CRUD)
            'create pipelines',
            'read pipelines',
            'update pipelines',
            'delete pipelines',

            // CUSTOMERS (CRUD)
            'read customers',
            'create customers',
            'update customers',
            'delete customers',

            // CRM (READ ONLY)
            'read crm',

            // REPORTS (CRUD)
            'read reports',
            'create reports',
            'update reports',
            'delete reports',
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
        $admin->syncPermissions([
            // companies → admin bisa baca data company sendiri (opsional)
            'read companies',
            'update companies',

            // team accounts CRUD (kelola tim dalam 1 perusahaan)
            'create team accounts',
            'read team accounts',
            'update team accounts',
            'delete team accounts',

            // pipelines CRUD
            'create pipelines',
            'read pipelines',
            'update pipelines',
            'delete pipelines',

            // customers CRUD
            'read customers',
            'create customers',
            'update customers',
            'delete customers',

            // crm
            'read crm',

            // reports CRUD
            'read reports',
            'create reports',
            'update reports',
            'delete reports',
        ]);

        // MARKETING:
        $marketing->syncPermissions([
            'read pipelines',
            // customers
            'read customers',
            'create customers',
            'update customers',

            // crm
            'read crm',

            // reports CRUD (kalau mau marketing bisa generate & edit laporan)
            'read reports',
            'create reports',
            'update reports',
            'delete reports',
        ]);

        // CS:
        $cs->syncPermissions([

            //
            'read pipelines',
            // customers
            'read customers',
            'update customers',

            // crm
            'read crm',


            // reports CRUD
            'read reports',
            'create reports',
            'update reports',
            'delete reports',
        ]);
    }
}