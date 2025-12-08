<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $companyId = Perusahaan::query()->value('id') ?? 1;

        // if (!$company || !$user) {
        //     $this->command->warn('Perusahaan atau User belum ada. ProductSeeder dilewati.');
        //     return;
        // }

        // === PRODUCT 1 (idempotent) ===
        $crmBasic = Product::updateOrCreate(
            ['slug' => 'paket-crm-basic'],
            [
                'company_id'  => $companyId,
                'name'        => 'Paket CRM Basic',
                'base_price'  => 1500000,
                'photo_path'  => 'products/crm-basic.jpg',
                'description' => 'Paket CRM Basic untuk tim kecil (hingga 5 user).',
                'created_by'  => $user?->id,
                'updated_by'  => $user?->id,
                'is_active'   => true,
            ]
        );

        ProductDetail::updateOrCreate(
            ['product_id' => $crmBasic->id, 'label' => 'Durasi Langganan'],
            ['value' => '3 bulan']
        );
        ProductDetail::updateOrCreate(
            ['product_id' => $crmBasic->id, 'label' => 'Maksimal User'],
            ['value' => '5 user']
        );
        ProductDetail::updateOrCreate(
            ['product_id' => $crmBasic->id, 'label' => 'Support'],
            ['value' => 'WA & Email (jam kerja)']
        );

        // === PRODUCT 2 (idempotent) ===
        $crmPro = Product::updateOrCreate(
            ['slug' => 'paket-crm-pro-wa'],
            [
                'company_id'  => $companyId,
                'name'        => 'Paket CRM Pro + WA Blast',
                'base_price'  => 4500000,
                'photo_path'  => 'products/crm-pro-wa.jpg',
                'description' => 'CRM Pro dengan fitur WhatsApp Blast untuk campaign.',
                'created_by'  => $user?->id,
                'updated_by'  => $user?->id,
                'is_active'   => true,
            ]
        );

        ProductDetail::updateOrCreate(
            ['product_id' => $crmPro->id, 'label' => 'Durasi Langganan'],
            ['value' => '6 bulan']
        );
        ProductDetail::updateOrCreate(
            ['product_id' => $crmPro->id, 'label' => 'Maksimal User'],
            ['value' => '20 user']
        );
        ProductDetail::updateOrCreate(
            ['product_id' => $crmPro->id, 'label' => 'Kuota WA Blast'],
            ['value' => '10.000 pesan / bulan']
        );

        $this->command->info('ProductSeeder selesai: 2 produk + details.');
    }
}
