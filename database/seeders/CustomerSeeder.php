<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'company_id'       => 1,
            'name'             => 'Budi Santoso',
            'phone'            => '081234567890',
            'email'            => 'budi@example.com',
            'source'           => 'Instagram',
            'tag'              => 'retail',
            'assigned_to_id'   => 1,
            'current_stage_id' => 1,
            'created_by'       => 1,
            'notes'            => 'Tertarik paket premium, follow up minggu depan.',
            'estimated_value'  => 1500000,
            'last_contact_at'  => Carbon::now()->subDays(2),
        ]);

        Customer::create([
            'company_id'       => 1,
            'name'             => 'Siti Rahma',
            'phone'            => '089876543210',
            'email'            => 'siti@example.com',
            'source'           => 'Referral',
            'tag'              => 'vip',
            'assigned_to_id'   => 1,
            'current_stage_id' => 2,
            'created_by'       => 1,
            'notes'            => 'Sudah minta proposal dan nego harga.',
            'estimated_value'  => 2500000,
            'last_contact_at'  => Carbon::now()->subDay(),
        ]);

        Customer::create([
            'company_id'       => 1,
            'name'             => 'Andi Pratama',
            'phone'            => '081222334455',
            'email'            => 'andi@example.com',
            'source'           => 'Website',
            'tag'              => 'new',
            'assigned_to_id'   => null,
            'current_stage_id' => 1,
            'created_by'       => 1,
            'notes'            => null,
            'estimated_value'  => null,
            'last_contact_at'  => null,
        ]);
    }
}