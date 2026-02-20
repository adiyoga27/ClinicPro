<?php

namespace App\Livewire\Admin;

use App\Models\ClinicQueue;
use App\Models\Patient;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class QueueManager extends Component
{
    public int $patient_id = 0;
    public int $doctor_id = 0;

    public function addToQueue(): void
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
        ]);

        $clinicId = auth()->user()->clinic_id;

        ClinicQueue::create([
            'clinic_id' => $clinicId,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'queue_no' => ClinicQueue::nextQueueNo($clinicId, today()->toDateString()),
            'date' => today(),
            'status' => 'waiting',
        ]);

        $this->reset(['patient_id', 'doctor_id']);
        session()->flash('success', 'Pasien berhasil ditambahkan ke antrian.');
    }

    public function render()
    {
        $clinicId = auth()->user()->clinic_id;

        return view('livewire.admin.queue-manager', [
            'queues' => ClinicQueue::where('date', today())
                ->with(['patient', 'doctor'])
                ->orderBy('queue_no')
                ->get(),
            'patients' => Patient::orderBy('name')->get(),
            'doctors' => User::where('clinic_id', $clinicId)->role('doctor')->orderBy('name')->get(),
        ]);
    }
}
