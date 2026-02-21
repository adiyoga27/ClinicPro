<?php

namespace App\Livewire\Cashier;

use App\Models\Billing;
use App\Models\Payment;
use App\Models\DepositTransaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class BillingManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'unpaid';
    public $selectedBilling = null;
    public array $paymentRows = [];
    public bool $showModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function selectBilling($billingId): void
    {
        $this->selectedBilling = Billing::with(['patient', 'items', 'medicalRecord.doctor'])->findOrFail($billingId);
        $this->paymentRows = [
            ['method' => 'cash', 'amount' => $this->selectedBilling->total_amount]
        ];
        $this->showModal = true;
    }

    public function addPaymentRow(): void
    {
        $this->paymentRows[] = ['method' => 'cash', 'amount' => 0];
    }

    public function removePaymentRow($index): void
    {
        unset($this->paymentRows[$index]);
        $this->paymentRows = array_values($this->paymentRows);
    }

    public function getTotalPaidProperty(): float
    {
        return collect($this->paymentRows)->sum(fn($row) => (float)($row['amount'] ?? 0));
    }

    public function processPayment(): void
    {
        $this->validate([
            'paymentRows.*.method' => 'required|in:cash,debit,qris,bpjs,deposit',
            'paymentRows.*.amount' => 'required|numeric|min:0',
        ]);

        if (abs($this->totalPaid - $this->selectedBilling->total_amount) > 0.01) {
            $this->addError('totalPaid', 'Total pembayaran (Rp ' . number_format($this->totalPaid, 0, ',', '.') . ') harus sama dengan total tagihan (Rp ' . number_format($this->selectedBilling->total_amount, 0, ',', '.') . ').');
            return;
        }

        $patient = $this->selectedBilling->patient;
        $totalDepositNeeded = collect($this->paymentRows)
            ->where('method', 'deposit')
            ->sum('amount');

        if ($totalDepositNeeded > 0 && $patient->deposit_balance < $totalDepositNeeded) {
            $this->addError('totalPaid', 'Saldo deposit pasien tidak mencukupi untuk pembayaran ini.');
            return;
        }

        DB::transaction(function () use ($patient) {
            foreach ($this->paymentRows as $row) {
                if ($row['amount'] <= 0) continue;

                // 1. Create Payment record for each method
                Payment::create([
                    'billing_id' => $this->selectedBilling->id,
                    'clinic_id' => auth()->user()->clinic_id,
                    'payment_method' => $row['method'],
                    'amount' => $row['amount'],
                    'status' => 'success',
                    'paid_at' => now(),
                ]);

                // 2. Handle Deposit deduction if applicable
                if ($row['method'] === 'deposit') {
                    DepositTransaction::create([
                        'patient_id' => $patient->id,
                        'clinic_id' => auth()->user()->clinic_id,
                        'user_id' => auth()->id(),
                        'amount' => $row['amount'],
                        'type' => 'usage',
                        'description' => 'Pembayaran Tagihan #' . $this->selectedBilling->id . ' (Split Payment)',
                    ]);

                    $patient->decrement('deposit_balance', $row['amount']);
                }
            }

            // 3. Update Billing status
            $this->selectedBilling->update([
                'status' => 'paid',
                'payment_method' => count($this->paymentRows) > 1 ? 'multiple' : $this->paymentRows[0]['method'],
            ]);
        });

        session()->flash('success', 'Pembayaran berhasil diproses.');
        $this->showModal = false;
        $this->selectedBilling = null;
    }

    public function render()
    {
        $billings = Billing::query()
            ->where('clinic_id', auth()->user()->clinic_id)
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->whereHas('patient', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('medical_record_no', 'like', "%{$this->search}%");
            })
            ->with(['patient', 'payments'])
            ->latest()
            ->paginate(10);

        return view('livewire.cashier.billing-manager', compact('billings'));
    }
}
