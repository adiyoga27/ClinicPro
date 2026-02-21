<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class PatientManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public bool $showForm = false;

    // Form fields
    public ?int $editingId = null;
    public string $name = '';
    public string $nik = '';
    public string $medical_record_no = '';
    public ?string $birth_date = null;
    public string $gender = '';
    public string $phone = '';
    public string $address = '';
    public string $blood_type = '';
    public string $mother_name = '';
    public string $mother_nik = '';
    public $photo; // For new upload
    public ?string $existingPhoto = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'nullable|digits:16',
            'medical_record_no' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'mother_name' => 'nullable|string|max:255',
            'mother_nik' => 'nullable|digits:16',
            'photo' => 'nullable|image|max:2048', // 2MB max
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'nik', 'medical_record_no', 'birth_date', 'gender', 'phone', 'address', 'blood_type', 'mother_name', 'mother_nik', 'photo', 'existingPhoto']);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $patient = Patient::findOrFail($id);
        $this->editingId = $patient->id;
        $this->name = $patient->name;
        $this->nik = $patient->nik ?? '';
        $this->medical_record_no = $patient->medical_record_no ?? '';
        $this->birth_date = $patient->birth_date?->format('Y-m-d');
        $this->gender = $patient->gender ?? '';
        $this->phone = $patient->phone ?? '';
        $this->address = $patient->address ?? '';
        $this->blood_type = $patient->blood_type ?? '';
        $this->mother_name = $patient->mother_name ?? '';
        $this->mother_nik = $patient->mother_nik ?? '';
        $this->existingPhoto = $patient->photo_path;
        $this->photo = null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'nik' => $this->nik ?: null,
            'medical_record_no' => $this->medical_record_no ?: null,
            'birth_date' => $this->birth_date ?: null,
            'gender' => $this->gender ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'blood_type' => $this->blood_type ?: null,
            'mother_name' => $this->mother_name ?: null,
            'mother_nik' => $this->mother_nik ?: null,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('patients', 'public');
        }

        if ($this->editingId) {
            Patient::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Pasien berhasil diperbarui.');
        } else {
            Patient::create($data);
            session()->flash('success', 'Pasien berhasil ditambahkan.');
        }

        $this->showForm = false;
        $this->reset(['editingId', 'name', 'nik', 'medical_record_no', 'birth_date', 'gender', 'phone', 'address', 'blood_type', 'mother_name', 'mother_nik', 'photo', 'existingPhoto']);
    }

    public array $syncLogs = [];
    public bool $showSyncLogs = false;

    public function closeSyncLogs(): void
    {
        $this->showSyncLogs = false;
        $this->syncLogs = [];
    }

    public function syncSatuSehat(int $id, \App\Services\SatuSehatService $satuSehatService): void
    {
        $patient = Patient::findOrFail($id);
        
        if (empty($patient->nik)) {
            session()->flash('error', 'Pasien ini belum memiliki NIK. Sinkronisasi identitas ke Satu Sehat membutuhkan NIK.');
            return;
        }

        $result = $satuSehatService->getPatientByNik($patient->nik);

        if ($result['success'] && isset($result['data']['id'])) {
            $patient->update(['satu_sehat_patient_id' => $result['data']['id']]);
            session()->flash('success', 'Berhasil melakukan sinkronisasi dengan Satu Sehat (ID: ' . $patient->satu_sehat_patient_id . ').');
        } else {
            session()->flash('error', 'Gagal sinkronisasi: ' . ($result['error'] ?? 'Data tidak ditemukan di database Kependudukan Kemendagri / Satu Sehat.'));
        }
    }

    public function pullFromSatuSehat(\App\Services\SatuSehatService $satuSehatService): void
    {
        $this->syncLogs = [];
        $result = $satuSehatService->getPatientsByOrganization();

        if ($result['success']) {
            $entries = $result['data']['entry'] ?? [];
            $count = 0;

            foreach ($entries as $entry) {
                $resource = $entry['resource'] ?? null;
                if ($resource && isset($resource['id'])) {
                    $existingPatient = Patient::where('satu_sehat_patient_id', $resource['id'])->first();
                    
                    if (!$existingPatient) {
                        // Try to get NIK
                        $nik = '';
                        $identifiers = $resource['identifier'] ?? [];
                        foreach ($identifiers as $identifier) {
                            if (isset($identifier['system']) && str_contains($identifier['system'], 'nik')) {
                                $nik = $identifier['value'] ?? '';
                                break;
                            }
                        }

                        $name = $resource['name'][0]['text'] ?? ($resource['name'][0]['given'][0] ?? 'Unknown Patient');

                        // Check if NIK exists
                        if ($nik) {
                            $patientByNik = Patient::where('nik', $nik)->first();
                            if ($patientByNik) {
                                // Just update the ID
                                $patientByNik->update(['satu_sehat_patient_id' => $resource['id']]);
                                $count++;
                                $this->syncLogs[] = [
                                    'status' => 'success',
                                    'message' => "Update ID Satu Sehat untuk Pasien $name (NIK: $nik)"
                                ];
                                continue;
                            }
                        }

                        // Create new patient
                        $gender = isset($resource['gender']) ? ($resource['gender'] == 'male' ? 'male' : 'female') : null;
                        $birthDate = $resource['birthDate'] ?? null;
                        
                        // Try to get address
                        $addressStr = '';
                        if (isset($resource['address']) && count($resource['address']) > 0) {
                            $addressLines = $resource['address'][0]['line'] ?? [];
                            $addressStr = implode(', ', $addressLines);
                        }

                        // Try to get phone
                        $phone = '';
                        if (isset($resource['telecom'])) {
                            foreach ($resource['telecom'] as $telecom) {
                                if (isset($telecom['system']) && $telecom['system'] === 'phone') {
                                    $phone = $telecom['value'] ?? '';
                                    break;
                                }
                            }
                        }

                        Patient::create([
                            'satu_sehat_patient_id' => $resource['id'],
                            'nik' => $nik ?: null,
                            'name' => $name,
                            'gender' => $gender,
                            'birth_date' => $birthDate,
                            'address' => $addressStr ?: null,
                            'phone' => $phone ?: null,
                        ]);
                        $count++;
                        $this->syncLogs[] = [
                            'status' => 'success',
                            'message' => "Berhasil menarik data Pasien Baru: $name"
                        ];
                    }
                }
            }

            if ($count > 0) {
                $this->showSyncLogs = true;
                session()->flash('success', "Proses penarikan selesai. Menarik $count pasien dari Satu Sehat.");
            } else {
                session()->flash('success', "Semua pasien dari Satu Sehat sudah ada di sistem (up-to-date).");
            }
        } else {
            session()->flash('error', 'Gagal menarik data pasien: ' . ($result['error'] ?? 'Terjadi kesalahan.'));
        }
    }

    public function bulkSyncSatuSehat(\App\Services\SatuSehatService $satuSehatService): void
    {
        $this->syncLogs = [];
        
        // Find patients with NIK but without Satu Sehat ID
        $patients = Patient::whereNotNull('nik')
            ->where('nik', '!=', '')
            ->whereNull('satu_sehat_patient_id')
            ->limit(50) // Limit to avoid timeout
            ->get();

        if ($patients->isEmpty()) {
            session()->flash('success', 'Tidak ada data pasien (dengan NIK) yang perlu disinkronisasi.');
            return;
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($patients as $patient) {
            $result = $satuSehatService->getPatientByNik($patient->nik);
            if ($result['success'] && isset($result['data']['id'])) {
                $patient->update(['satu_sehat_patient_id' => $result['data']['id']]);
                $successCount++;
                $this->syncLogs[] = [
                    'status' => 'success',
                    'message' => "Sinkronisasi berhasil untuk Pasien {$patient->name} (NIK: {$patient->nik})"
                ];
            } else {
                $failCount++;
                $errorMsg = is_string($result['error']) ? $result['error'] : 'Data tidak ditemukan / NIK Invalid';
                
                $this->syncLogs[] = [
                    'status' => 'error',
                    'message' => "Gagal sinkron pasien {$patient->name} (NIK: {$patient->nik}) - Error: {$errorMsg}"
                ];
            }
        }

        $this->showSyncLogs = true;
        
        $message = "Proses sinkronisasi massal selesai. $successCount berhasil.";
        if ($failCount > 0) {
            $message .= " $failCount NIK tidak valid/tidak ditemukan.";
        }
        
        session()->flash('success', $message);
    }

    public function delete(int $id): void
    {
        Patient::findOrFail($id)->delete();
        session()->flash('success', 'Pasien berhasil dihapus.');
    }

    public function render()
    {
        $patients = Patient::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->orWhere('medical_record_no', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.patient-manager', compact('patients'));
    }
}
