<?php

namespace App\Livewire\Admin;

use App\Models\ClinicQueue;
use App\Models\Patient;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $clinicId = auth()->user()->clinic_id;

        return view('livewire.admin.dashboard', [
            'totalPatients' => Patient::count(),
            'todayQueue' => ClinicQueue::where('date', today())->count(),
            'staffCount' => User::where('clinic_id', $clinicId)->count(),
            'todayVisits' => ClinicQueue::where('date', today())->where('status', 'completed')->count(),
        ]);
    }
}
