<?php
$user = \App\Models\User::first();
auth()->login($user);

$record = \App\Models\MedicalRecord::with('patient', 'diagnoses.icd10Code', 'doctor', 'queue.room')->latest()->first();
$service = app(\App\Services\SatuSehatService::class);
$result = $service->syncMedicalRecord($record);
echo json_encode($result, JSON_PRETTY_PRINT);
