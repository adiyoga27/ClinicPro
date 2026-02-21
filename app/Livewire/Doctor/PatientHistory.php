<?php

namespace App\Livewire\Doctor;

use App\Models\Patient;
use App\Models\MedicalRecord;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PatientHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $nikSearch = '';
    public $dobSearch = '';
    
    public $recordSearchDate = ''; // New filter for records
    
    public $selectedPatientId = null; // Store ID instead of full object for easier management
    public $showDetail = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'nikSearch' => ['except' => ''],
        'dobSearch' => ['except' => ''],
        'recordSearchDate' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingNikSearch() { $this->resetPage(); }
    public function updatingDobSearch() { $this->resetPage(); }
    public function updatingRecordSearchDate() { $this->resetPage('records-page'); }

    public function selectPatient($patientId)
    {
        $this->selectedPatientId = $patientId;
        $this->showDetail = true;
        $this->resetPage('records-page');
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedPatientId = null;
        $this->recordSearchDate = '';
    }

    public function render()
    {
        $patients = Patient::where('clinic_id', auth()->user()->clinic_id)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->nikSearch, function ($query) {
                $query->where('nik', 'like', '%' . $this->nikSearch . '%');
            })
            ->when($this->dobSearch, function ($query) {
                $query->whereDate('birth_date', $this->dobSearch);
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'patients-page');

        $selectedPatient = null;
        $medicalRecords = null;

        if ($this->showDetail && $this->selectedPatientId) {
            $selectedPatient = Patient::where('clinic_id', auth()->user()->clinic_id)
                ->findOrFail($this->selectedPatientId);

            $medicalRecords = MedicalRecord::with(['diagnoses.icd10Code', 'doctor', 'prescription.items'])
                ->where('patient_id', $this->selectedPatientId)
                ->when($this->recordSearchDate, function ($query) {
                    $query->whereDate('visit_date', $this->recordSearchDate);
                })
                ->orderBy('visit_date', 'desc')
                ->paginate(5, ['*'], 'records-page');
        }

        return view('livewire.doctor.patient-history', [
            'patients' => $patients,
            'selectedPatient' => $selectedPatient,
            'medicalRecords' => $medicalRecords,
        ]);
    }
}
