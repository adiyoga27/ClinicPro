<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use App\Models\DepositTransaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class DepositManager extends Component
{
    use WithPagination;

    public string $search = '';
    public $selectedPatient = null;
    public float $amount = 0;
    public string $description = '';
    public string $type = 'topup';
    public bool $showModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectPatient($patientId): void
    {
        $this->selectedPatient = Patient::with(['depositTransactions' => function($q) {
            $q->latest()->limit(10);
        }])->findOrFail($patientId);
        $this->showModal = true;
    }

    public function processTransaction(): void
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:topup,usage',
            'description' => 'required|string|max:255',
        ]);

        if ($this->type === 'usage' && $this->selectedPatient->deposit_balance < $this->amount) {
            $this->addError('amount', 'Saldo deposit tidak mencukupi.');
            return;
        }

        DB::transaction(function () {
            DepositTransaction::create([
                'patient_id' => $this->selectedPatient->id,
                'clinic_id' => auth()->user()->clinic_id,
                'user_id' => auth()->id(),
                'amount' => $this->amount,
                'type' => $this->type,
                'description' => $this->description,
            ]);

            $newBalance = $this->type === 'topup' 
                ? $this->selectedPatient->deposit_balance + $this->amount 
                : $this->selectedPatient->deposit_balance - $this->amount;

            $this->selectedPatient->update(['deposit_balance' => $newBalance]);
        });

        session()->flash('success', 'Transaksi deposit berhasil diproses.');
        $this->reset(['amount', 'description', 'type']);
        $this->selectPatient($this->selectedPatient->id); // Refresh data
    }

    public function render()
    {
        $patients = Patient::query()
            ->where('clinic_id', auth()->user()->clinic_id)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->orWhere('medical_record_no', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.deposit-manager', compact('patients'));
    }
}
