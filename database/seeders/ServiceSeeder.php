<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinic = Clinic::first();
        if (!$clinic) {
            $this->command->warn('Tidak ada klinik di database. Pastikan ClinicSeeder sudah dijalankan lebih dulu.');
            return;
        }

        $services = [
            [
                'name' => 'Jasa Dokter Umum',
                'price' => 50000,
                'is_active' => true,
                'is_automatic' => true,
            ],
            [
                'name' => 'Jasa Admin / Registrasi',
                'price' => 10000,
                'is_active' => true,
                'is_automatic' => true,
            ],
            [
                'name' => 'Nebulizer',
                'price' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'Cek Gula Darah (GDS)',
                'price' => 25000,
                'is_active' => true,
            ],
            [
                'name' => 'Cek Kolesterol',
                'price' => 40000,
                'is_active' => true,
            ],
            [
                'name' => 'Cek Asam Urat',
                'price' => 30000,
                'is_active' => true,
            ],
            [
                'name' => 'Ganti Perban Kecil',
                'price' => 35000,
                'is_active' => true,
            ],
            [
                'name' => 'Injeksi / Suntik',
                'price' => 40000,
                'is_active' => true,
            ],
            [
                'name' => 'EKG (Rekam Jantung)',
                'price' => 85000,
                'is_active' => true,
            ]
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'name' => $srv['name'],
                ],
                $srv
            );
        }

        $this->command->info('Data Jasa & Tindakan Medis (Services) berhasil diseeding!');
    }
}
