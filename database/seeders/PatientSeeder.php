<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Pastikan ada klinik setidaknya 1 buah di database untuk diassign sbg clinic_id
        $clinic = Clinic::first();
        if (!$clinic) {
            $this->command->warn('Tidak ada klinik di database. Pastikan ClinicSeeder sudah dijalankan lebih dulu.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $name = $faker->name($gender);

            Patient::create([
                'clinic_id' => $clinic->id,
                'medical_record_no' => 'RM-' . date('Ym') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nik' => $faker->nik(),
                'name' => $name,
                'birth_date' => $faker->dateTimeBetween('-60 years', '-5 years')->format('Y-m-d'),
                'gender' => $gender,
                'address' => $faker->address(),
                'phone' => $faker->phoneNumber(),
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O', null]),
                // Generate avatar URL using ui-avatars to create a nice-looking initial avatar
                'photo_path' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random&color=fff&size=200',
                'mother_name' => $faker->name('female'),
                'deposit_balance' => 0,
            ]);
        }

        $this->command->info('20 Pasien berhasil diseeding beserta avatarnya!');
    }
}
