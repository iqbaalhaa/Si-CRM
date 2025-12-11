<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Perusahaan default utama (dipakai oleh user seed)
        Perusahaan::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'name'    => 'Perusahaan Utama',
                'address' => 'Alamat Perusahaan Utama',
                'phone'   => '080000000000',
                'email'   => 'info@utama.example',
                'status'  => 'active',
            ]
        );

        // Buat COMP1 .. COMP5 agar UserSeeder bisa memilih random company_id 1..5
        for ($i = 1; $i <= 5; $i++) {
            $code = 'COMP' . $i;
            Perusahaan::firstOrCreate(
                ['code' => $code],
                [
                    'name'    => 'Perusahaan ' . $i,
                    'address' => "Alamat Perusahaan {$i}",
                    'phone'   => '0812' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'email'   => "company{$i}@example.com",
                    'status'  => 'active',
                ]
            );
        }
    }
}