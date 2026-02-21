<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Icd10Code;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = ['superadmin', 'admin', 'doctor', 'cashier', 'patient'];
        foreach ($roles as $role) {
            Role::findOrCreate($role, 'web');
        }

        // Create Superadmin User (no clinic)
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@clinicpro.test'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'is_active' => true,
            ]
        );
        $superadmin->assignRole('superadmin');

        // Create a demo clinic
        $clinic = Clinic::firstOrCreate(
            ['slug' => 'klinik-sehat-sentosa'],
            [
                'name' => 'Klinik Sehat Sentosa',
                'phone' => '021-55512345',
                'email' => 'admin@kliniksehat.test',
                'address' => 'Jl. Kesehatan No. 1, Jakarta Selatan',
                'status' => 'active',
            ]
        );

        // Create subscription for demo clinic
        $clinic->subscriptions()->firstOrCreate(
            ['plan' => 'professional'],
            [
                'price' => 599000,
                'started_at' => now(),
                'expired_at' => now()->addYear(),
                'status' => 'active',
            ]
        );

        // Create demo users for the clinic
        $admin = User::firstOrCreate(
            ['email' => 'admin@kliniksehat.test'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Dr. Admin Klinik',
                'password' => 'password',
                'phone' => '08123456789',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        $doctor = User::firstOrCreate(
            ['email' => 'dokter@kliniksehat.test'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Dr. Budi Santoso',
                'password' => 'password',
                'phone' => '08123456790',
                'is_active' => true,
            ]
        );
        $doctor->assignRole('doctor');

        $cashier = User::firstOrCreate(
            ['email' => 'kasir@kliniksehat.test'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Siti Kasir',
                'password' => 'password',
                'phone' => '08123456791',
                'is_active' => true,
            ]
        );
        $cashier->assignRole('cashier');

        // Seed ICD-10 codes (Satu Sehat compliant)
        $this->call(Icd10Seeder::class);

        // Seed other demo data
        $this->call([
            PatientSeeder::class,
            MedicineSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
