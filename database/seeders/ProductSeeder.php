<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        $user    = User::first();

        // if (!$company || !$user) {
        //     $this->command->warn('Perusahaan atau User belum ada. ProductSeeder dilewati.');
        //     return;
        // }

        // === PRODUCT 1 ===
        $crmBasic = Product::create([
            'company_id'  => 1,
            'name'        => 'Paket CRM Basic',
            'slug'        => 'paket-crm-basic',
            'base_price'  => 1500000,
            'photo_path'  => 'products/crm-basic.jpg',
            'description' => 'Paket CRM Basic untuk tim kecil (hingga 5 user).',
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
            'is_active'   => true,
        ]);

        ProductDetail::insert([
            [
                'product_id' => $crmBasic->id,
                'label'      => 'Durasi Langganan',
                'value'      => '3 bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $crmBasic->id,
                'label'      => 'Maksimal User',
                'value'      => '5 user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $crmBasic->id,
                'label'      => 'Support',
                'value'      => 'WA & Email (jam kerja)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === PRODUCT 2 ===
        $crmPro = Product::create([
            'company_id'  => 1,
            'name'        => 'Paket CRM Pro + WA Blast',
            'slug'        => 'paket-crm-pro-wa',
            'base_price'  => 4500000,
            'photo_path'  => 'products/crm-pro-wa.jpg',
            'description' => 'CRM Pro dengan fitur WhatsApp Blast untuk campaign.',
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
            'is_active'   => true,
        ]);

        ProductDetail::insert([
            [
                'product_id' => $crmPro->id,
                'label'      => 'Durasi Langganan',
                'value'      => '6 bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $crmPro->id,
                'label'      => 'Maksimal User',
                'value'      => '20 user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $crmPro->id,
                'label'      => 'Kuota WA Blast',
                'value'      => '10.000 pesan / bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('ProductSeeder selesai: 2 produk + details.');
    }
}