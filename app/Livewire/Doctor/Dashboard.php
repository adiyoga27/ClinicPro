<?php

namespace App\Livewire\Doctor;

use App\Models\ClinicQueue;
use App\Models\MedicalRecord;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $doctorId = auth()->id();

        return view('livewire.doctor.dashboard', [
            'myQueue' => ClinicQueue::where('doctor_id', $doctorId)
                ->where('date', today())
                ->whereIn('status', ['waiting', 'in_progress'])
                ->with('patient')
                ->orderBy('queue_no')
                ->get(),
            'completedToday' => ClinicQueue::where('doctor_id', $doctorId)
                ->where('date', today())
                ->where('status', 'completed')
                ->count(),
            'totalRecords' => MedicalRecord::where('doctor_id', $doctorId)->count(),
        ]);
    }
}
