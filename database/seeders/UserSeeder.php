<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Company;
use App\Models\Perusahaan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role yang dibutuhkan ada
        foreach (['super-admin', 'admin', 'lead-operations'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        // Pastikan ada 1 perusahaan default
        $company = Perusahaan::firstOrCreate(
            ['code' => 'MAIN'], // key unik
            [
                'name'    => 'Perusahaan Utama',
                'address' => 'Alamat default',
                'phone'   => '080000000000',
                'email'   => 'info@example.com',
                'status'  => 'active',
            ]
        );

        // ======================
        // SUPER ADMIN
        // ======================
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Profile untuk super admin
        Profile::firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'company_id' => $company->id,
                'job_title'  => 'Super Admin',
                'photo'      => null,
            ]
        );

        // ======================
        // ADMIN
        // ======================
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin Utama',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Profile untuk admin
        Profile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'company_id' => $company->id,
                'job_title'  => 'Admin Utama',
                'photo'      => null,
            ]
        );

        // ======================
        // LEAD OPERATIONS
        // ======================
        $leadOperations = User::firstOrCreate(
            ['email' => 'lead-operations@example.com'],
            [
                'name'     => 'Lead Operations Depati',
                'password' => bcrypt('password'),
            ]
        );
        $leadOperations->assignRole('lead-operations');

        // Profile untuk lead-operations
        Profile::firstOrCreate(
            ['user_id' => $leadOperations->id],
            [
                'company_id' => $company->id,
                'job_title'  => 'Lead Operations',
                'photo'      => null,
            ]
        );
    }
}