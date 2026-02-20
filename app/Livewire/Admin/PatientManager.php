<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PatientManager extends Component
{
    use WithPagination;

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
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'nik', 'medical_record_no', 'birth_date', 'gender', 'phone', 'address', 'blood_type']);
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
        ];

        if ($this->editingId) {
            Patient::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Pasien berhasil diperbarui.');
        } else {
            Patient::create($data);
            session()->flash('success', 'Pasien berhasil ditambahkan.');
        }

        $this->showForm = false;
        $this->reset(['editingId', 'name', 'nik', 'medical_record_no', 'birth_date', 'gender', 'phone', 'address', 'blood_type']);
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
