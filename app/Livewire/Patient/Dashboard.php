<?php

namespace App\Livewire\Patient;

use App\Models\MedicalRecord;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.patient.dashboard');
    }
}
