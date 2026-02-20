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

    // Step 4: Plan & Prescriptions
    public $plan = '';
    public $medicineSearch = '';
    public $prescriptionItems = [];

    // Computed
    public $doctorFee = 0;

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
        $this->doctorFee = auth()->user()->clinic->doctor_fee ?? 50000;

        // Mark queue as in_progress
        if ($queue->status === 'waiting') {
            $queue->update(['status' => 'in_progress']);
        }
    }

    // Navigation
    public function nextStep()
    {
        $this->validateCurrentStep();
        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $step)
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->step) {
            $this->step = $step;
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
    }

    public function removeDiagnosis($index)
    {
        unset($this->selectedDiagnoses[$index]);
        $this->selectedDiagnoses = array_values($this->selectedDiagnoses);

        // Reassign primary if needed
        if (!empty($this->selectedDiagnoses) && !collect($this->selectedDiagnoses)->contains('type', 'primary')) {
            $this->selectedDiagnoses[0]['type'] = 'primary';
        }
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
    }

    public function removeMedicine($index)
    {
        unset($this->prescriptionItems[$index]);
        $this->prescriptionItems = array_values($this->prescriptionItems);
    }

    public function updateQty($index, $qty)
    {
        if (isset($this->prescriptionItems[$index])) {
            $this->prescriptionItems[$index]['qty'] = max(1, (int) $qty);
        }
    }

    // Billing calculation
    public function getTotalBillingProperty()
    {
        $medicineTotal = collect($this->prescriptionItems)->sum(fn($item) => $item['price'] * $item['qty']);
        return $this->doctorFee + $medicineTotal;
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

            // Doctor fee item
            BillingItem::create([
                'billing_id' => $billing->id,
                'name' => 'Jasa Dokter',
                'qty' => 1,
                'unit_price' => $this->doctorFee,
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

            $billing->recalculate();

            // 5. Update queue status
            $this->queue->update(['status' => 'completed']);
        });

        session()->flash('success', 'Pemeriksaan berhasil disimpan!');
        return redirect()->route('doctor.dashboard');
    }

    public function render()
    {
        return view('livewire.doctor.patient-examination');
    }
}
