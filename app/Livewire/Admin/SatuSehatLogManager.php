<?php

namespace App\Livewire\Admin;

use App\Models\SatuSehatLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SatuSehatLogManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = SatuSehatLog::query()
            ->with(['medicalRecord.patient'])
            ->where('clinic_id', auth()->user()->clinic_id)
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('medicalRecord.patient', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('medical_record_no', 'like', "%{$this->search}%");
                })
                ->orWhere('resource_type', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.satu-sehat-log-manager', compact('logs'));
    }
}
