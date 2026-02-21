<?php

namespace App\Livewire\Doctor;

use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\ClinicQueue;
use App\Models\Diagnosis;
use App\Models\Icd10Code;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PatientExamination extends Component
{
    // Queue & patient
    public $queue;
    public $patient;

    // Stepper
    public int $step = 1;
    public int $totalSteps = 5;

    // Step 1: Vital Signs
    public $height;
    public $weight;
    public $blood_pressure_systolic;
    public $blood_pressure_diastolic;
    public $temperature;
    public $heart_rate;
    public $respiratory_rate;
    public $spo2;
    public $allergy_notes;

    // Step 2: Subjective
    public $subjective = '';

    // Step 3: Objective & Diagnosis
    public $objective = '';
    public $assessment = '';
    public $icdSearch = '';
    public $selectedDiagnoses = [];
    public $showIcdModal = false;
    public $icdModalSearch = '';
    public $icdModalResults = [];

    // Step 4: Plan, Prescriptions, & Services
    public $plan = '';
    public $medicineSearch = '';
    public $prescriptionItems = [];
    public $serviceSearch = '';
    public $selectedServices = [];

    // Computed

    public function mount(ClinicQueue $queue)
    {
        // Ensure doctor owns this queue
        if ($queue->doctor_id !== auth()->id()) {
            abort(403);
        }

        // Ensure queue is actionable
        if (!in_array($queue->status, ['waiting', 'in_progress'])) {
            session()->flash('error', 'Pasien ini sudah selesai diperiksa.');
            return redirect()->route('doctor.dashboard');
        }

        $this->queue = $queue;
        $this->patient = $queue->patient;

        // Mark queue as in_progress
        if ($queue->status === 'waiting') {
            $queue->update(['status' => 'in_progress']);
        }

        // Load Draft if exists
        $draft = Cache::get('exam_draft_' . $queue->id);
        if ($draft) {
            $this->step = $draft['step'] ?? 1;
            $this->height = $draft['height'] ?? null;
            $this->weight = $draft['weight'] ?? null;
            $this->blood_pressure_systolic = $draft['blood_pressure_systolic'] ?? null;
            $this->blood_pressure_diastolic = $draft['blood_pressure_diastolic'] ?? null;
            $this->temperature = $draft['temperature'] ?? null;
            $this->heart_rate = $draft['heart_rate'] ?? null;
            $this->respiratory_rate = $draft['respiratory_rate'] ?? null;
            $this->spo2 = $draft['spo2'] ?? null;
            $this->allergy_notes = $draft['allergy_notes'] ?? null;
            $this->subjective = $draft['subjective'] ?? '';
            $this->objective = $draft['objective'] ?? '';
            $this->assessment = $draft['assessment'] ?? '';
            $this->selectedDiagnoses = $draft['selectedDiagnoses'] ?? [];
            $this->plan = $draft['plan'] ?? '';
            $this->prescriptionItems = $draft['prescriptionItems'] ?? [];
            $this->selectedServices = $draft['selectedServices'] ?? [];
        } else {
            // Load automatic services if no draft exists
            $autoServices = \App\Models\Service::where('clinic_id', auth()->user()->clinic_id)
                ->where('is_active', true)
                ->where('is_automatic', true)
                ->get();

            foreach ($autoServices as $srv) {
                $this->selectedServices[] = [
                    'service_id' => $srv->id,
                    'name' => $srv->name,
                    'price' => $srv->price,
                ];
            }
        }
    }

    // Auto-Save Draft
    public function saveDraft()
    {
        Cache::put('exam_draft_' . $this->queue->id, [
            'step' => $this->step,
            'height' => $this->height,
            'weight' => $this->weight,
            'blood_pressure_systolic' => $this->blood_pressure_systolic,
            'blood_pressure_diastolic' => $this->blood_pressure_diastolic,
            'temperature' => $this->temperature,
            'heart_rate' => $this->heart_rate,
            'respiratory_rate' => $this->respiratory_rate,
            'spo2' => $this->spo2,
            'allergy_notes' => $this->allergy_notes,
            'subjective' => $this->subjective,
            'objective' => $this->objective,
            'assessment' => $this->assessment,
            'selectedDiagnoses' => $this->selectedDiagnoses,
            'plan' => $this->plan,
            'prescriptionItems' => $this->prescriptionItems,
            'selectedServices' => $this->selectedServices,
        ], now()->addHours(12));
    }

    // Navigation
    public function nextStep()
    {
        $this->saveDraft();
        $this->validateCurrentStep();
        if ($this->step < $this->totalSteps) {
            $this->step++;
            $this->saveDraft();
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->saveDraft();
        }
    }

    public function goToStep(int $step)
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->step) {
            $this->step = $step;
            $this->saveDraft();
        }
    }

    private function validateCurrentStep()
    {
        match ($this->step) {
            2 => $this->validate(['subjective' => 'required|min:3'], [
                'subjective.required' => 'Keluhan utama wajib diisi.',
                'subjective.min' => 'Keluhan minimal 3 karakter.',
            ]),
            default => null,
        };
    }

    // ICD-10 Search
    public function getIcdResultsProperty()
    {
        if (strlen($this->icdSearch) < 2)
            return [];

        return Icd10Code::where(function ($q) {
            $q->where('code', 'like', "%{$this->icdSearch}%")
                ->orWhere('name_en', 'like', "%{$this->icdSearch}%")
                ->orWhere('name_id', 'like', "%{$this->icdSearch}%");
        })->limit(10)->get()->toArray();
    }

    public function addDiagnosis($icdId)
    {
        $icd = Icd10Code::find($icdId);
        if (!$icd)
            return;

        // Prevent duplicates
        foreach ($this->selectedDiagnoses as $d) {
            if ($d['id'] == $icdId)
                return;
        }

        $this->selectedDiagnoses[] = [
            'id' => $icd->id,
            'code' => $icd->code,
            'name' => $icd->name_id ?: $icd->name_en,
            'type' => count($this->selectedDiagnoses) === 0 ? 'primary' : 'secondary',
        ];

        $this->icdSearch = '';
        $this->saveDraft();
    }

    public function removeDiagnosis($index)
    {
        unset($this->selectedDiagnoses[$index]);
        $this->selectedDiagnoses = array_values($this->selectedDiagnoses);

        // Reassign primary if needed
        if (!empty($this->selectedDiagnoses) && !collect($this->selectedDiagnoses)->contains('type', 'primary')) {
            $this->selectedDiagnoses[0]['type'] = 'primary';
        }
        $this->saveDraft();
    }

    // Modal ICD-10 Search
    public function searchIcdModal()
    {
        if (strlen($this->icdModalSearch) < 2) {
            $this->icdModalResults = [];
            return;
        }

        $this->icdModalResults = Icd10Code::where(function ($q) {
            $q->where('code', 'like', "%{$this->icdModalSearch}%")
                ->orWhere('name_en', 'like', "%{$this->icdModalSearch}%")
                ->orWhere('name_id', 'like', "%{$this->icdModalSearch}%");
        })->limit(50)->get()->toArray();
    }

    public function updatedIcdModalSearch()
    {
        $this->searchIcdModal();
    }

    public function openIcdModal()
    {
        $this->icdModalSearch = '';
        $this->icdModalResults = [];
        $this->showIcdModal = true;
    }

    // Medicine Search
    public function getMedicineResultsProperty()
    {
        if (strlen($this->medicineSearch) < 2)
            return [];

        return Medicine::where('clinic_id', auth()->user()->clinic_id)
            ->active()
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->medicineSearch}%")
                    ->orWhere('generic_name', 'like', "%{$this->medicineSearch}%");
            })
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function addMedicine($medicineId)
    {
        $medicine = Medicine::find($medicineId);
        if (!$medicine)
            return;

        // Prevent duplicates
        foreach ($this->prescriptionItems as $item) {
            if ($item['medicine_id'] == $medicineId)
                return;
        }

        $this->prescriptionItems[] = [
            'medicine_id' => $medicine->id,
            'drug_name' => $medicine->name,
            'price' => $medicine->price,
            'qty' => 1,
            'dosage' => '',
            'frequency' => '3x sehari',
            'duration' => '3 hari',
            'notes' => '',
            'unit' => $medicine->unit,
        ];

        $this->medicineSearch = '';
        $this->saveDraft();
    }

    public function removeMedicine($index)
    {
        unset($this->prescriptionItems[$index]);
        $this->prescriptionItems = array_values($this->prescriptionItems);
        $this->saveDraft();
    }

    public function updateQty($index, $qty)
    {
        if (isset($this->prescriptionItems[$index])) {
            $this->prescriptionItems[$index]['qty'] = max(1, (int) $qty);
            $this->saveDraft();
        }
    }

    // Service Search
    public function getServiceResultsProperty()
    {
        if (strlen($this->serviceSearch) < 2)
            return [];

        return \App\Models\Service::where('clinic_id', auth()->user()->clinic_id)
            ->active()
            ->where('name', 'like', "%{$this->serviceSearch}%")
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function addService($serviceId)
    {
        $service = \App\Models\Service::find($serviceId);
        if (!$service)
            return;

        // Prevent duplicates
        foreach ($this->selectedServices as $item) {
            if ($item['service_id'] == $serviceId)
                return;
        }

        $this->selectedServices[] = [
            'service_id' => $service->id,
            'name' => $service->name,
            'price' => $service->price,
        ];

        $this->serviceSearch = '';
        $this->saveDraft();
    }

    public function removeService($index)
    {
        unset($this->selectedServices[$index]);
        $this->selectedServices = array_values($this->selectedServices);
        $this->saveDraft();
    }

    // Billing calculation
    public function getTotalBillingProperty()
    {
        $medicineTotal = collect($this->prescriptionItems)->sum(fn($item) => $item['price'] * $item['qty']);
        $serviceTotal = collect($this->selectedServices)->sum('price');
        return $medicineTotal + $serviceTotal;
    }

    // Save everything
    public function saveExamination()
    {
        $this->validate([
            'subjective' => 'required|min:3',
        ], [
            'subjective.required' => 'Keluhan utama wajib diisi.',
        ]);

        DB::transaction(function () {
            // 1. Create Medical Record
            $record = MedicalRecord::create([
                'clinic_id' => auth()->user()->clinic_id,
                'patient_id' => $this->patient->id,
                'doctor_id' => auth()->id(),
                'queue_id' => $this->queue->id,
                'visit_date' => today(),
                'subjective' => $this->subjective,
                'objective' => $this->objective,
                'assessment' => $this->assessment,
                'plan' => $this->plan,
                'height' => $this->height,
                'weight' => $this->weight,
                'blood_pressure_systolic' => $this->blood_pressure_systolic,
                'blood_pressure_diastolic' => $this->blood_pressure_diastolic,
                'temperature' => $this->temperature,
                'heart_rate' => $this->heart_rate,
                'respiratory_rate' => $this->respiratory_rate,
                'spo2' => $this->spo2,
                'allergy_notes' => $this->allergy_notes,
            ]);

            // 2. Create Diagnoses
            foreach ($this->selectedDiagnoses as $diagnosis) {
                Diagnosis::create([
                    'medical_record_id' => $record->id,
                    'icd10_code_id' => $diagnosis['id'],
                    'type' => $diagnosis['type'],
                ]);
            }

            // 3. Create Prescription + Items
            if (!empty($this->prescriptionItems)) {
                $prescription = Prescription::create([
                    'medical_record_id' => $record->id,
                    'clinic_id' => auth()->user()->clinic_id,
                ]);

                foreach ($this->prescriptionItems as $item) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id' => $item['medicine_id'],
                        'drug_name' => $item['drug_name'],
                        'dosage' => $item['dosage'],
                        'frequency' => $item['frequency'],
                        'duration' => $item['duration'],
                        'price' => $item['price'],
                        'qty' => $item['qty'],
                        'notes' => $item['notes'],
                    ]);
                }
            }

            // 4. Create Billing
            $billing = Billing::create([
                'clinic_id' => auth()->user()->clinic_id,
                'patient_id' => $this->patient->id,
                'medical_record_id' => $record->id,
                'total_amount' => 0,
                'status' => 'unpaid',
            ]);

            // Medicine items
            foreach ($this->prescriptionItems as $item) {
                BillingItem::create([
                    'billing_id' => $billing->id,
                    'name' => 'Obat: ' . $item['drug_name'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['price'],
                ]);
            }

            // Additional Service items
            foreach ($this->selectedServices as $item) {
                BillingItem::create([
                    'billing_id' => $billing->id,
                    'name' => 'Tindakan: ' . $item['name'],
                    'qty' => 1,
                    'unit_price' => $item['price'],
                ]);
            }

            $billing->recalculate();

            // 5. Update queue status
            $this->queue->update(['status' => 'completed']);
        });

        // Clear Draft
        Cache::forget('exam_draft_' . $this->queue->id);

        session()->flash('success', 'Pemeriksaan berhasil disimpan!');
        return redirect()->route('doctor.dashboard');
    }

    public function render()
    {
        return view('livewire.doctor.patient-examination');
    }
}
