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
    public int $room_id = 0;
    public bool $showForm = false;

    public function create(): void
    {
        $this->reset(['patient_id', 'doctor_id', 'room_id']);
        $this->showForm = true;
    }

    public function addToQueue(): void
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
        ], [
            'patient_id.exists' => 'Silakan pilih pasien.',
            'doctor_id.exists' => 'Silakan pilih dokter.',
            'room_id.exists' => 'Silakan pilih ruangan / poli.',
        ]);

        $clinicId = auth()->user()->clinic_id;

        ClinicQueue::create([
            'clinic_id' => $clinicId,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'room_id' => $this->room_id,
            'queue_no' => ClinicQueue::nextQueueNo($clinicId, today()->toDateString()),
            'date' => today(),
            'status' => 'waiting',
        ]);

        $this->reset(['patient_id', 'doctor_id', 'room_id']);
        $this->showForm = false;
        session()->flash('success', 'Pasien berhasil ditambahkan ke antrian.');
    }

    public function render()
    {
        $clinicId = auth()->user()->clinic_id;

        return view('livewire.admin.queue-manager', [
            'queues' => ClinicQueue::where('date', today())
                ->with(['patient', 'doctor', 'room'])
                ->orderBy('queue_no')
                ->get(),
            'patients' => Patient::orderBy('name')->get(),
            'doctors' => User::where('clinic_id', $clinicId)->role('doctor')->orderBy('name')->get(),
            'rooms' => \App\Models\Room::where('clinic_id', $clinicId)->orderBy('name')->get(),
        ]);
    }
}
