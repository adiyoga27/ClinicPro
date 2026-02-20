<?php

namespace Database\Seeders;

use App\Models\Icd10Code;
use Illuminate\Database\Seeder;

class Icd10Seeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/icd10_codes.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command->error("Could not open CSV file.");
            return;
        }

        // Skip the header row
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return;
        }

        $batch = [];
        $batchSize = 500;
        $total = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2)
                continue;

            $code = trim($row[0]);
            $display = trim($row[1]);

            if (empty($code))
                continue;

            $batch[] = [
                'code' => $code,
                'name_en' => $display,
                'name_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                $this->upsertBatch($batch);
                $total += count($batch);
                $batch = [];
            }
        }

        // Insert remaining records
        if (!empty($batch)) {
            $this->upsertBatch($batch);
            $total += count($batch);
        }

        fclose($handle);

        $this->command->info("Seeded {$total} ICD-10 codes from CSV.");
    }

    private function upsertBatch(array $batch): void
    {
        Icd10Code::upsert($batch, ['code'], ['name_en', 'name_id', 'updated_at']);
    }
}
