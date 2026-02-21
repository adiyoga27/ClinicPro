<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
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

        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Acetaminophen',
                'category' => 'tablet',
                'unit' => 'pcs',
                'price' => 5000,
                'stock' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'category' => 'kapsul',
                'unit' => 'pcs',
                'price' => 10000,
                'stock' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'category' => 'kapsul',
                'unit' => 'pcs',
                'price' => 15000,
                'stock' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'OBH Combi Plus 100ml',
                'generic_name' => null,
                'category' => 'sirup',
                'unit' => 'botol',
                'price' => 20000,
                'stock' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Cetirizine 10mg',
                'generic_name' => 'Cetirizine',
                'category' => 'tablet',
                'unit' => 'pcs',
                'price' => 8000,
                'stock' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Salbutamol 2mg',
                'generic_name' => 'Salbutamol',
                'category' => 'tablet',
                'unit' => 'pcs',
                'price' => 4500,
                'stock' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Amlodipine 5mg',
                'generic_name' => 'Amlodipine Besylate',
                'category' => 'tablet',
                'unit' => 'pcs',
                'price' => 12000,
                'stock' => 400,
                'is_active' => true,
            ],
            [
                'name' => 'Vitamin C IPI',
                'generic_name' => 'Ascorbic Acid',
                'category' => 'tablet',
                'unit' => 'tube',
                'price' => 7500,
                'stock' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($medicines as $med) {
            Medicine::updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'name' => $med['name'],
                ],
                $med
            );
        }

        $this->command->info('Data Obat (Medicines) berhasil diseeding!');
    }
}
