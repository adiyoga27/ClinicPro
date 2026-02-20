<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $this->addError('email', 'Akun Anda telah dinonaktifkan.');
            return;
        }

        session()->regenerate();

        // Redirect based on role
        if ($user->hasRole('superadmin')) {
            $this->redirect(route('superadmin.dashboard'), navigate: true);
        } elseif ($user->hasRole('admin')) {
            $this->redirect(route('admin.dashboard'), navigate: true);
        } elseif ($user->hasRole('doctor')) {
            $this->redirect(route('doctor.dashboard'), navigate: true);
        } elseif ($user->hasRole('cashier')) {
            $this->redirect(route('cashier.dashboard'), navigate: true);
        } else {
            $this->redirect(route('patient.dashboard'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
