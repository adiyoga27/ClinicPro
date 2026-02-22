<?php
$user = \App\Models\User::first();
auth()->login($user);

$record = \App\Models\MedicalRecord::with('patient', 'diagnoses.icd10Code', 'doctor', 'queue.room')->latest()->first();

$user = \App\Models\User::where('clinic_id', $record->clinic_id)->first() ?? \App\Models\User::first();
auth()->login($user);

$service = app(\App\Services\SatuSehatService::class);
$result = $service->syncMedicalRecord($record);
file_put_contents('ss_output.json', json_encode($result, JSON_PRETTY_PRINT));
