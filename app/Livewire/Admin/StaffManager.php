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
    public array $selectedRoles = [];
    public bool $showForm = false;
    public ?int $editingId = null;
    public bool $showRoleModal = false;
    public ?int $roleUserId = null;
    public string $roleUserName = '';
    public array $editRoles = [];

    public function create(): void
    {
        $this->reset(['name', 'email', 'password', 'phone', 'selectedRoles', 'editingId']);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'in:admin,doctor,cashier',
        ], [
            'selectedRoles.required' => 'Pilih minimal satu role.',
            'selectedRoles.min' => 'Pilih minimal satu role.',
        ]);

        $user = User::create([
            'clinic_id' => auth()->user()->clinic_id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone ?: null,
            'is_active' => true,
        ]);

        $user->syncRoles($this->selectedRoles);

        $this->showForm = false;
        $this->reset(['name', 'email', 'password', 'phone', 'selectedRoles']);
        session()->flash('success', 'Staf berhasil ditambahkan.');
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
