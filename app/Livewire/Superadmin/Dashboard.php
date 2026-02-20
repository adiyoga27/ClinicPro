<?php

namespace App\Livewire\Superadmin;

use App\Models\Clinic;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.superadmin.dashboard', [
            'totalClinics' => Clinic::count(),
            'activeClinics' => Clinic::where('status', 'active')->count(),
            'blockedClinics' => Clinic::where('status', 'blocked')->count(),
            'activeSubscriptions' => Subscription::where('status', 'active')->where('expired_at', '>', now())->count(),
            'totalUsers' => User::count(),
            'recentClinics' => Clinic::latest()->limit(5)->get(),
        ]);
    }
}
