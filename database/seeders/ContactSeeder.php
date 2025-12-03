<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\ContactChannel;
use App\Models\ContactDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // Pakai company_id 3 secara default
        $companyId = 3;

        // Pastikan user yang dipakai juga dari company_id 3
        $users = User::where('company_id', $companyId)->pluck('id')->all();
        if (empty($users)) {
            $user = User::factory()->create(['company_id' => $companyId]);
            $users = [$user->id];
        }

        $types = ['individual', 'company', 'organization'];

        // Kota/kabupaten di Provinsi Jambi
        // Kerinci & Sungai Penuh dibuat lebih sering muncul
        $jambiCities = [
            'Kerinci','Kerinci','Kerinci','Kerinci','Kerinci',
            'Sungai Penuh','Sungai Penuh','Sungai Penuh','Sungai Penuh',
            'Jambi','Merangin','Sarolangun','Batang Hari','Muaro Jambi',
            'Tebo','Bungo','Tanjung Jabung Timur','Tanjung Jabung Barat',
        ];

        for ($i = 0; $i < 100; $i++) {
            $type = Arr::random($types);

            // Nama kontak
            if ($type === 'individual') {
                $name = fake()->name();
            } elseif ($type === 'company') {
                $name = fake()->company();
            } else {
                $name = 'Organisasi ' . ucfirst(fake()->word());
            }

            // Buat contact
            $contact = Contact::create([
                'company_id' => $companyId,
                'type'       => $type,
                'name'       => $name,
                'is_active'  => fake()->boolean(80),
                'created_by' => Arr::random($users),
            ]);

            // Channel utama (email / phone / whatsapp)
            $channelType = Arr::random(['email', 'phone', 'whatsapp']);

            $value = match ($channelType) {
                'email'    => fake()->safeEmail(),
                'phone'    => '08' . fake()->numberBetween(111111111, 999999999), // nomor HP Indonesia
                'whatsapp' => '+62' . fake()->numberBetween(81100000000, 85999999999), // WA Indonesia
                default    => null,
            };

            ContactChannel::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label'      => $channelType,
                'value'      => $value,
                'is_primary' => 1,
            ]);

            // Detail tambahan sesuai tipe kontak
            if ($type === 'individual') {
                $details = [
                    'alamat_lengkap'     => fake()->streetAddress(),
                    'kota_kabupaten'     => Arr::random($jambiCities),
                    'provinsi'           => 'Jambi',
                    'negara'             => 'Indonesia',
                    'jenis_kelamin'      => Arr::random(['laki-laki', 'perempuan']),
                    'tanggal_lahir'      => fake()->date('Y-m-d', '-20 years'),
                    'agama'              => Arr::random(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu']),
                    'status_pernikahan'  => Arr::random(['lajang','menikah']),
                ];
            } elseif ($type === 'company') {
                $details = [
                    'nama_brand'         => fake()->company(),
                    'industri'           => Arr::random(['Teknologi','Manufaktur','Retail','Kesehatan','Keuangan']),
                    'npwp'               => (string) fake()->numberBetween(1000000000, 9999999999),
                    'alamat_lengkap'     => fake()->streetAddress(),
                    'kota_kabupaten'     => Arr::random($jambiCities),
                    'provinsi'           => 'Jambi',
                    'negara'             => 'Indonesia',
                    'jumlah_karyawan'    => (string) fake()->numberBetween(5, 2000),
                ];
            } else { // organization
                $details = [
                    'tipe_organisasi'    => Arr::random(['Yayasan','Komunitas','LSM']),
                    'bidang_kegiatan'    => Arr::random(['Sosial','Lingkungan','Pendidikan','Kesehatan']),
                    'jumlah_anggota'     => (string) fake()->numberBetween(10, 500),
                    'alamat_lengkap'     => fake()->streetAddress(),
                    'kota_kabupaten'     => Arr::random($jambiCities),
                    'provinsi'           => 'Jambi',
                    'negara'             => 'Indonesia',
                ];
            }

            // Simpan detail
            foreach ($details as $label => $val) {
                ContactDetail::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label'      => $label,
                    'value'      => (string) $val,
                ]);
            }
        }
    }
}
