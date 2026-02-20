<?php

namespace App\Console\Commands;

use App\Jobs\SyncToSatuSehat;
use App\Models\SatuSehatLog;
use Illuminate\Console\Command;

class RetrySatuSehatSync extends Command
{
    protected $signature = 'satu-sehat:retry-failed';
    protected $description = 'Retry failed Satu Sehat FHIR sync entries (max 3 attempts)';

    public function handle(): int
    {
        $failedLogs = SatuSehatLog::where('status', 'failed')
            ->where('attempts', '<', 3)
            ->get();

        if ($failedLogs->isEmpty()) {
            $this->info('No failed Satu Sehat logs to retry.');
            return self::SUCCESS;
        }

        $this->info("Retrying {$failedLogs->count()} failed sync entries...");

        foreach ($failedLogs as $log) {
            SyncToSatuSehat::dispatch($log->medical_record_id, $log->clinic_id);
            $this->line("  → Dispatched retry for medical_record #{$log->medical_record_id}");
        }

        $this->info('Done. Jobs dispatched to queue.');
        return self::SUCCESS;
    }
}
