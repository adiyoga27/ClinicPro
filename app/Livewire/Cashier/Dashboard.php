<?php

namespace App\Livewire\Cashier;

use App\Models\Billing;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.cashier.dashboard', [
            'pendingBills' => Billing::where('status', 'unpaid')->with('patient')->latest()->limit(10)->get(),
            'unpaidCount' => Billing::where('status', 'unpaid')->count(),
            'paidTodayCount' => Billing::where('status', 'paid')->whereDate('updated_at', today())->count(),
            'todayRevenue' => Billing::where('status', 'paid')->whereDate('updated_at', today())->sum('total_amount'),
        ]);
    }
}
