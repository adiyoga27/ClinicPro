<?php

namespace App\Jobs;

use App\Models\MedicalRecord;
use App\Models\SatuSehatLog;
use App\Services\SatuSehatService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncToSatuSehat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // 1 min between retries

    public function __construct(
        public int $medicalRecordId,
        public int $clinicId
    ) {
    }

    public function handle(SatuSehatService $service): void
    {
        $medicalRecord = MedicalRecord::with(['patient', 'doctor', 'diagnoses.icd10Code'])
            ->find($this->medicalRecordId);

        if (!$medicalRecord) {
            Log::warning("SyncToSatuSehat: Medical record #{$this->medicalRecordId} not found.");
            return;
        }

        // Create or find the log entry
        $log = SatuSehatLog::updateOrCreate(
            [
                'medical_record_id' => $this->medicalRecordId,
                'clinic_id' => $this->clinicId,
            ],
            [
                'resource_type' => 'Bundle',
                'status' => 'processing',
                'last_attempted_at' => now(),
            ]
        );

        try {
            $results = $service->syncMedicalRecord($medicalRecord);

            // Check if all sync steps succeeded
            $allSuccess = true;
            if (isset($results['encounter']) && !$results['encounter']['success']) {
                $allSuccess = false;
            }

            $log->update([
                'payload' => $results,
                'response' => $results,
                'status' => $allSuccess ? 'success' : 'partial',
                'attempts' => $log->attempts + 1,
                'error_message' => $allSuccess ? null : 'Some resources failed to sync',
            ]);

        } catch (\Throwable $e) {
            Log::error("SyncToSatuSehat: Failed for record #{$this->medicalRecordId}", [
                'error' => $e->getMessage(),
            ]);

            $log->update([
                'status' => 'failed',
                'attempts' => $log->attempts + 1,
                'error_message' => $e->getMessage(),
            ]);

            throw $e; // Re-throw so Laravel handles retry
        }
    }
}
