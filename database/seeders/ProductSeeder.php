<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->all();
        if (empty($users)) {
            $user = User::factory()->create();
            $users = [$user->id];
        }

        $companyIds = [1, 2, 3, 4, 5];

        $productTemplates = [
            ['slug' => 'paket-crm-basic', 'name' => 'Paket CRM Basic', 'price' => 1500000, 'photo' => 'products/crm-basic.jpg', 'desc' => 'Paket CRM Basic untuk tim kecil (hingga 5 user).', 'details' => ['Durasi Langganan' => '3 bulan', 'Maksimal User' => '5 user', 'Support' => 'WA & Email (jam kerja)']],
            ['slug' => 'paket-crm-pro-wa', 'name' => 'Paket CRM Pro + WA Blast', 'price' => 4500000, 'photo' => 'products/crm-pro-wa.jpg', 'desc' => 'CRM Pro dengan fitur WhatsApp Blast untuk campaign.', 'details' => ['Durasi Langganan' => '6 bulan', 'Maksimal User' => '20 user', 'Kuota WA Blast' => '10.000 pesan / bulan']],
            ['slug' => 'paket-crm-enterprise', 'name' => 'Paket CRM Enterprise', 'price' => 10000000, 'photo' => 'products/crm-enterprise.jpg', 'desc' => 'Solusi CRM lengkap untuk enterprise dengan custom feature.', 'details' => ['Durasi Langganan' => '12 bulan', 'Maksimal User' => 'Unlimited', 'Support' => '24/7 Dedicated', 'Custom Feature' => 'Available']],
            ['slug' => 'paket-email-marketing', 'name' => 'Paket Email Marketing', 'price' => 2000000, 'photo' => 'products/email-marketing.jpg', 'desc' => 'Email marketing automation untuk campaign efektif.', 'details' => ['Durasi' => '3 bulan', 'Kuota Email' => '100.000 / bulan', 'Template' => '50+ template']],
            ['slug' => 'paket-sms-gateway', 'name' => 'Paket SMS Gateway', 'price' => 1000000, 'photo' => 'products/sms-gateway.jpg', 'desc' => 'SMS gateway untuk notifikasi dan campaign SMS.', 'details' => ['Durasi' => '6 bulan', 'Kuota SMS' => '50.000 pesan', 'Coverage' => 'Seluruh Indonesia']],
            ['slug' => 'paket-whatsapp-business', 'name' => 'Paket WhatsApp Business API', 'price' => 3500000, 'photo' => 'products/wa-business.jpg', 'desc' => 'WhatsApp Business API untuk komunikasi customer.', 'details' => ['Durasi' => '6 bulan', 'Kuota Pesan' => '100.000 / bulan', 'Support' => '24/7']],
            ['slug' => 'paket-dashboard-analytics', 'name' => 'Paket Dashboard & Analytics', 'price' => 2500000, 'photo' => 'products/dashboard-analytics.jpg', 'desc' => 'Dashboard dan analytics mendalam untuk business intelligence.', 'details' => ['Durasi' => '12 bulan', 'Report Custom' => '20 reports/bulan', 'Export' => 'PDF, Excel, CSV']],
            ['slug' => 'paket-crm-lite', 'name' => 'Paket CRM Lite', 'price' => 800000, 'photo' => 'products/crm-lite.jpg', 'desc' => 'CRM ringan untuk freelancer dan startup.', 'details' => ['Durasi' => '1 bulan', 'Maksimal User' => '2 user', 'Storage' => '5 GB']],
            ['slug' => 'paket-crm-team', 'name' => 'Paket CRM Team', 'price' => 5000000, 'photo' => 'products/crm-team.jpg', 'desc' => 'CRM untuk tim menengah dengan fitur kolaborasi.', 'details' => ['Durasi' => '12 bulan', 'Maksimal User' => '15 user', 'Kolaborasi' => 'Real-time', 'API Access' => 'Yes']],
            ['slug' => 'paket-training-support', 'name' => 'Paket Training & Support', 'price' => 3000000, 'photo' => 'products/training-support.jpg', 'desc' => 'Training dan dedicated support untuk implementasi.', 'details' => ['Durasi' => '3 bulan', 'Training Session' => '10 sesi', 'Dedicated Support' => '1 person', 'Documentation' => 'Custom']],
        ];

        // Generate 100 produk: loop 10x dengan setiap template di-variasi per company
        for ($i = 0; $i < 100; $i++) {
            $template = $productTemplates[$i % count($productTemplates)];
            $companyId = Arr::random($companyIds);
            $userId = Arr::random($users);

            // Buat slug unik dengan menambahkan suffix
            $slug = $template['slug'] . '-' . ($i + 1);

            // Buat nama unik dengan variant
            $name = $template['name'] . ' (v' . ($i + 1) . ')';

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'company_id' => $companyId,
                    'name' => $name,
                    'base_price' => $template['price'] + rand(-500000, 500000), // Variasi harga sedikit
                    'photo_path' => $template['photo'],
                    'description' => $template['desc'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                    'is_active' => fake()->boolean(85),
                ]
            );

            foreach ($template['details'] as $label => $value) {
                ProductDetail::updateOrCreate(
                    ['product_id' => $product->id, 'label' => $label],
                    ['value' => $value]
                );
            }
        }
    }
}
