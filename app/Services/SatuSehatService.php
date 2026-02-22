<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SatuSehatService
{
    protected string $baseUrl;
    protected string $authUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $organizationId;

    public function __construct()
    {
        $this->baseUrl = config('satu_sehat.base_url');
        $this->authUrl = config('satu_sehat.auth_url');
        
        // Fetch credentials from the current clinic if authenticated, 
        // fallback to config (even though it's empty) for safety.
        $clinic = auth()->check() ? auth()->user()->clinic : null;
        
        $this->clientId = (string) ($clinic?->satusehat_client_id ?? config('satu_sehat.client_id') ?? '');
        $this->clientSecret = (string) ($clinic?->satusehat_client_secret ?? config('satu_sehat.client_secret') ?? '');
        $this->organizationId = (string) ($clinic?->satusehat_organization_id ?? config('satu_sehat.organization_id') ?? '');
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    /**
     * Get OAuth2 access token (cached for 55 minutes).
     */
    public function getAccessToken(): ?string
    {
        return Cache::remember('satu_sehat_token', 3300, function () {
            if (empty($this->clientId) || empty($this->clientSecret)) {
                Log::warning('SatuSehat: Client credentials not configured.');
                return null;
            }

            $response = Http::asForm()->post($this->authUrl . '?grant_type=client_credentials', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('SatuSehat: Failed to get access token.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        });
    }

    /**
     * Send a FHIR resource to Satu Sehat.
     */
    public function sendResource(string $resourceType, array $payload): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'error' => 'No access token available'];
        }

        $url = $this->baseUrl . '/fhir-r4/v1' . ($resourceType ? '/' . $resourceType : '');

        // Explicitly json_encode the payload since we are using a custom content-type 
        // to prevent Laravel from sending it in a wrong format
        $response = Http::withToken($token)
            ->withBody(json_encode($payload, JSON_UNESCAPED_SLASHES), 'application/fhir+json')
            ->post($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'status' => $response->status(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    /**
     * Get Practitioner by NIK
     */
    public function getPractitionerByNik(string $nik): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'error' => 'No access token available'];
        }

        $url = $this->baseUrl . '/fhir-r4/v1/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            $entries = $data['entry'] ?? [];
            
            if (count($entries) > 0) {
                // Return the first practitioner found
                return [
                    'success' => true,
                    'data' => $entries[0]['resource'],
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Practitioner tidak ditemukan untuk NIK tersebut.',
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
        ];
    }

    /**
     * Get Patient by NIK
     */
    public function getPatientByNik(string $nik): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'error' => 'No access token available'];
        }

        $url = $this->baseUrl . '/fhir-r4/v1/Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            $entries = $data['entry'] ?? [];
            
            if (count($entries) > 0) {
                // Return the first patient found
                return [
                    'success' => true,
                    'data' => $entries[0]['resource'],
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Patient tidak ditemukan untuk NIK tersebut.',
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
        ];
    }

    /**
     * Get Locations by Organization ID
     */
    public function getLocations(): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'error' => 'No access token available'];
        }

        $url = $this->baseUrl . '/fhir-r4/v1/Location?organization=' . $this->organizationId;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
        ];
    }

    /**
     * Get Patients by Organization ID
     */
    public function getPatientsByOrganization(): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'error' => 'No access token available'];
        }

        // According to FHIR specs, we can query patients by organization
        $url = $this->baseUrl . '/fhir-r4/v1/Patient?organization=' . $this->organizationId;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
        ];
    }

    /**
     * Build FHIR Patient resource from local patient model.
     */
    public function buildPatientResource($patient): array
    {
        return [
            'resourceType' => 'Patient',
            'identifier' => [
                [
                    'use' => 'official',
                    'system' => 'https://fhir.kemkes.go.id/id/nik',
                    'value' => $patient->nik,
                ],
            ],
            'name' => [
                [
                    'use' => 'official',
                    'text' => $patient->name,
                ],
            ],
            'gender' => $patient->gender === 'L' ? 'male' : 'female',
            'birthDate' => $patient->birth_date?->format('Y-m-d'),
            'multipleBirthInteger' => 0,
            'address' => [
                [
                    'use' => 'home',
                    'line' => [$patient->address ?? ''],
                    'extension' => [
                        [
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                            'extension' => [
                                [
                                    'url' => 'province',
                                    'valueCode' => '31' // Dummy DKI Jakarta
                                ],
                                [
                                    'url' => 'city',
                                    'valueCode' => '3171' // Dummy Jakarta Pusat
                                ],
                                [
                                    'url' => 'district',
                                    'valueCode' => '317101' // Dummy Gambir
                                ],
                                [
                                    'url' => 'village',
                                    'valueCode' => '3171011001' // Dummy Gambir
                                ],
                                [
                                    'url' => 'rt',
                                    'valueCode' => '1'
                                ],
                                [
                                    'url' => 'rw',
                                    'valueCode' => '1'
                                ]
                            ]
                        ]
                    ]
                ],
            ],
        ];
    }

    /**
     * Build FHIR Encounter resource from local medical record.
     */
    public function buildEncounterResource($medicalRecord, array $conditionUuids = []): array
    {
        $medicalRecord->loadMissing('diagnoses.icd10Code');
        $diagnoses = [];
        foreach ($medicalRecord->diagnoses as $idx => $diagnosis) {
            $reference = isset($conditionUuids[$diagnosis->id])
                ? 'urn:uuid:' . $conditionUuids[$diagnosis->id]
                : 'urn:uuid:' . (string) \Illuminate\Support\Str::uuid();

            $diagnoses[] = [
                'condition' => [
                    // 'reference' => $reference,
                    'display' => $diagnosis->icd10Code->name_en ?? 'Diagnosis'
                ],
                'use' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
                            'code' => 'DD',
                            'display' => 'Discharge diagnosis'
                        ]
                    ]
                ],
                'rank' => $idx + 1
            ];
        }

        $payload = [
            'resourceType' => 'Encounter',
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/encounter/' . $this->organizationId,
                    'value' => 'ENC-' . $medicalRecord->id
                ]
            ],
            'status' => 'finished',
            'statusHistory' => [
                [
                    'status' => 'arrived',
                    'period' => [
                        'start' => $medicalRecord->created_at->toIso8601String(),
                        'end' => $medicalRecord->created_at->addMinutes(5)->toIso8601String(),
                    ]
                ],
                [
                    'status' => 'in-progress',
                    'period' => [
                        'start' => $medicalRecord->created_at->addMinutes(5)->toIso8601String(),
                        'end' => $medicalRecord->created_at->addMinutes(20)->toIso8601String(),
                    ]
                ],
                [
                    'status' => 'finished',
                    'period' => [
                        'start' => $medicalRecord->created_at->addMinutes(20)->toIso8601String(),
                        'end' => $medicalRecord->created_at->addMinutes(30)->toIso8601String(),
                    ]
                ]
            ],
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
                'display' => 'ambulatory',
            ],
            'subject' => [
                'reference' => 'Patient/' . ($medicalRecord->patient->satu_sehat_id ?? ''),
                'display' => $medicalRecord->patient->name,
            ],
            'participant' => [
                [
                    'individual' => [
                        'display' => $medicalRecord->doctor->name ?? 'Doctor',
                    ],
                ],
            ],
            'period' => [
                'start' => $medicalRecord->created_at->toIso8601String(),
                'end' => $medicalRecord->created_at->addMinutes(30)->toIso8601String(),
            ],
            'serviceProvider' => [
                'reference' => 'Organization/' . $this->organizationId,
            ],
            'location' => [
                [
                    'location' => [
                        'reference' => 'Location/' . ($medicalRecord->queue->room->satusehat_id ?? ''),
                        'display' => $medicalRecord->queue->room->name ?? 'Ruangan',
                    ],
                ],
            ],
        ];

        if (!empty($diagnoses)) {
            $payload['diagnosis'] = $diagnoses;
        }

        return $payload;
    }

    /**
     * Build FHIR Condition (Diagnosis) resource.
     */
    public function buildConditionResource($diagnosis, string $encounterReference, string $patientId = null): array
    {
        $patientId = $patientId ?? ($diagnosis->medicalRecord->patient->satu_sehat_id ?? '');
        
        return [
            'resourceType' => 'Condition',
            'clinicalStatus' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                        'code' => 'active',
                        'display' => 'Active',
                    ],
                ],
            ],
            'category' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
                            'code' => 'encounter-diagnosis',
                            'display' => 'Encounter Diagnosis',
                        ],
                    ],
                ],
            ],
            'code' => [
                'coding' => [
                    [
                        'system' => 'http://hl7.org/fhir/sid/icd-10',
                        'code' => $diagnosis->icd10Code->code,
                        'display' => $diagnosis->icd10Code->name_en,
                    ],
                ],
            ],
            'subject' => [
                'reference' => 'Patient/' . $patientId,
            ],
            'encounter' => [
                'reference' => str_starts_with($encounterReference, 'urn:uuid:') ? $encounterReference : 'Encounter/' . $encounterReference,
            ],
        ];
    }

    /**
     * Sync a complete medical record (Patient, Encounter, Conditions) to Satu Sehat.
     */
    public function syncMedicalRecord($medicalRecord): array
    {
        $results = [];

        // 1. Sync Patient (if not already synced)
        $patient = $medicalRecord->patient;
        if (!$patient->satu_sehat_id) {
            $patientPayload = $this->buildPatientResource($patient);
            $patientResult = $this->sendResource('Patient', $patientPayload);

            // Handle duplicate error by fetching existing patient
            if (!$patientResult['success'] && str_contains(json_encode($patientResult), 'Found duplicate: Patient')) {
                $existing = $this->getPatientByNik($patient->nik);
                if ($existing['success'] && isset($existing['data']['id'])) {
                    $patientResult = [
                        'success' => true,
                        'data' => $existing['data'],
                        'status' => 200,
                        'note' => 'Fetched existing duplicate patient'
                    ];
                }
            }

            \App\Models\SatuSehatLog::create([
                'clinic_id' => $medicalRecord->clinic_id,
                'medical_record_id' => $medicalRecord->id,
                'resource_type' => 'Patient',
                'payload' => $patientPayload,
                'response' => $patientResult['data'] ?? ($patientResult['error'] ?? []),
                'status' => $patientResult['success'] ? 'success' : 'failed',
                'last_attempted_at' => now(),
                'error_message' => $patientResult['success'] ? null : ($patientResult['error'] ?? 'Unknown error'),
            ]);

            if ($patientResult['success'] && isset($patientResult['data']['id'])) {
                $patient->update(['satu_sehat_id' => $patientResult['data']['id']]);
                $patient->satu_sehat_id = $patientResult['data']['id'];
                $medicalRecord->patient->satu_sehat_id = $patientResult['data']['id']; // Ensure relations hold the ID for Encounter logic
            }
            $results['patient'] = $patientResult;
        }

        // 2. Build Bundle for Encounter & Conditions
        $encounterUuid = (string) \Illuminate\Support\Str::uuid();
        $conditionUuids = [];
        $medicalRecord->loadMissing('diagnoses.icd10Code');
        
        foreach ($medicalRecord->diagnoses as $diagnosis) {
            $conditionUuids[$diagnosis->id] = (string) \Illuminate\Support\Str::uuid();
        }

        $bundle = [
            'resourceType' => 'Bundle',
            'type' => 'transaction',
            'entry' => []
        ];

        // Condition Entries FIRST (to avoid HAPI FHIR crash with circular refs)
        foreach ($medicalRecord->diagnoses as $diagnosis) {
            $conditionId = $conditionUuids[$diagnosis->id];
            $conditionPayload = $this->buildConditionResource($diagnosis, 'urn:uuid:' . $encounterUuid, $patient->satu_sehat_id);
            $bundle['entry'][] = [
                'fullUrl' => 'urn:uuid:' . $conditionId,
                'resource' => $conditionPayload,
                'request' => [
                    'method' => 'POST',
                    'url' => 'Condition'
                ]
            ];
        }

        // Encounter Entry AFTER Conditions
        $encounterPayload = $this->buildEncounterResource($medicalRecord, $conditionUuids);
        $bundle['entry'][] = [
            'fullUrl' => 'urn:uuid:' . $encounterUuid,
            'resource' => $encounterPayload,
            'request' => [
                'method' => 'POST',
                'url' => 'Encounter'
            ]
        ];

        // 3. Send Bundle
        $bundleResult = $this->sendResource('', $bundle); // Empty string for base URL /fhir-r4/v1

        \App\Models\SatuSehatLog::create([
            'clinic_id' => $medicalRecord->clinic_id,
            'medical_record_id' => $medicalRecord->id,
            'resource_type' => 'Bundle',
            'payload' => $bundle,
            'response' => $bundleResult['data'] ?? ($bundleResult['error'] ?? []),
            'status' => $bundleResult['success'] ? 'success' : 'failed',
            'last_attempted_at' => now(),
            'error_message' => $bundleResult['success'] ? null : ($bundleResult['error'] ?? 'Unknown error'),
        ]);

        if ($bundleResult['success'] && isset($bundleResult['data']['entry'])) {
            // Find Encounter ID from response
            $encounterId = null;
            foreach ($bundleResult['data']['entry'] as $entry) {
                if (isset($entry['response']['location']) && str_contains($entry['response']['location'], 'Encounter/')) {
                    // Location usually looks like: Encounter/ac46ecbd-efbc-496a-a1cc-a34db5dedcc6/_history/1
                    preg_match('/Encounter\/([A-Za-z0-9\-]+)/', $entry['response']['location'], $matches);
                    if (!empty($matches[1])) {
                        $encounterId = $matches[1];
                        break;
                    }
                }
            }
            if ($encounterId) {
                $medicalRecord->update(['satu_sehat_encounter_id' => $encounterId]);
            }
        }
        
        $results['bundle'] = $bundleResult;

        return $results;
    }
}
