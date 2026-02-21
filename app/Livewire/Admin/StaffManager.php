<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffManager extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $phone = '';
    public string $nik = '';
    public array $selectedRoles = [];
    public bool $showForm = false;
    public ?int $editingId = null;
    public bool $showRoleModal = false;
    public ?int $roleUserId = null;
    public string $roleUserName = '';
    public array $editRoles = [];

    public function create(): void
    {
        $this->reset(['name', 'email', 'password', 'phone', 'nik', 'selectedRoles', 'editingId']);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|digits:16',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'in:admin,doctor,cashier',
        ], [
            'selectedRoles.required' => 'Pilih minimal satu role.',
            'selectedRoles.min' => 'Pilih minimal satu role.',
            'nik.digits' => 'NIK harus 16 digit angka.',
        ]);

        $user = User::create([
            'clinic_id' => auth()->user()->clinic_id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone ?: null,
            'nik' => $this->nik ?: null,
            'is_active' => true,
        ]);

        $user->syncRoles($this->selectedRoles);

        $this->showForm = false;
        $this->reset(['name', 'email', 'password', 'phone', 'nik', 'selectedRoles']);
        session()->flash('success', 'Staf berhasil ditambahkan.');
    }

    public function syncSatuSehat(int $id, \App\Services\SatuSehatService $satuSehatService)
    {
        $user = User::findOrFail($id);
        
        if (empty($user->nik)) {
            session()->flash('error', 'Staf ini belum memiliki NIK. Pembaharuan data dokter di DTO Kemenkes membutuhkan NIK.');
            return;
        }

        $result = $satuSehatService->getPractitionerByNik($user->nik);

        if ($result['success'] && isset($result['data']['id'])) {
            $user->update(['satusehat_id' => $result['data']['id']]);
            session()->flash('success', 'Berhasil melakukan sinkronisasi dengan Satu Sehat (ID: ' . $user->satusehat_id . ').');
        } else {
            session()->flash('error', 'Gagal sinkronisasi: ' . ($result['error'] ?? 'Data tidak ditemukan di SISDMK/Satu Sehat.'));
        }
    }

    public function openRoleModal(int $id): void
    {
        $user = User::findOrFail($id);
        $this->roleUserId = $user->id;
        $this->roleUserName = $user->name;
        $this->editRoles = $user->roles->pluck('name')->toArray();
        $this->showRoleModal = true;
    }

    public function saveRoles(): void
    {
        $this->validate([
            'editRoles' => 'required|array|min:1',
            'editRoles.*' => 'in:admin,doctor,cashier',
        ], [
            'editRoles.required' => 'Pilih minimal satu role.',
            'editRoles.min' => 'Pilih minimal satu role.',
        ]);

        $user = User::findOrFail($this->roleUserId);
        $user->syncRoles($this->editRoles);

        $this->showRoleModal = false;
        $this->reset(['roleUserId', 'roleUserName', 'editRoles']);
        session()->flash('success', 'Role berhasil diperbarui.');
    }

    public function toggleActive(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'Tidak bisa menonaktifkan akun sendiri.');
            return;
        }

        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.staff-manager', [
            'users' => User::where('clinic_id', auth()->user()->clinic_id)
                ->with('roles')
                ->latest()
                ->get(),
        ]);
    }
}
