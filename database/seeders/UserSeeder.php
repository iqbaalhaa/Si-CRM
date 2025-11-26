<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // SUPER ADMIN
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('password'),
                // 'company_id' => 1, 
            ]
        );
        $superAdmin->assignRole('super-admin');

        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin Utama',
                'password' => bcrypt('password'),
                // 'company_id' => 1,
            ]
        );
        $admin->assignRole('admin');

        // MARKETING
        $marketing = User::firstOrCreate(
            ['email' => 'marketing@example.com'],
            [
                'name'     => 'Marketing Depati',
                'password' => bcrypt('password'),
                // 'company_id' => 1,
            ]
        );
        $marketing->assignRole('marketing');

        // CS
        $cs = User::firstOrCreate(
            ['email' => 'cs@example.com'],
            [
                'name'     => 'Customer Service',
                'password' => bcrypt('password'),
                // 'company_id' => 1,
            ]
        );
        $cs->assignRole('cs');
    }
}