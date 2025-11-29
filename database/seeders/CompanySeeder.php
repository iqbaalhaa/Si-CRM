<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Perusahaan::create([
            'name' => 'PT Contoh Makmur',
            'code' => 'CM01',
            'address' => 'Jl. Mawar No. 123, Bandung',
            'phone' => '081234567890',
            'email' => 'info@contoh.com',
            'status' => 'active',
        ]);

        Perusahaan::create([
            'name' => 'CV Suka Jaya',
            'code' => 'SJ02',
            'address' => 'Jl. Melati No. 45, Jakarta',
            'phone' => '081987654321',
            'email' => 'cs@sukajaya.com',
            'status' => 'active',
        ]);
    }
}
