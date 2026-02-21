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

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->nik = $user->nik ?? '';
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->password = '';
        $this->showForm = true;
    }

    public function save(\App\Services\SatuSehatService $satuSehatService): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->editingId ? ',' . $this->editingId : ''),
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|digits:16',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'in:admin,doctor,cashier',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|min:6';
        } elseif (!empty($this->password)) {
            $rules['password'] = 'min:6';
        }

        $this->validate($rules, [
            'selectedRoles.required' => 'Pilih minimal satu role.',
            'selectedRoles.min' => 'Pilih minimal satu role.',
            'nik.digits' => 'NIK harus 16 digit angka.',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'nik' => $this->nik ?: null,
        ];

        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
            $message = 'Staf berhasil diperbarui.';
        } else {
            $data['clinic_id'] = auth()->user()->clinic_id;
            $data['is_active'] = true;
            $user = User::create($data);
            $message = 'Staf berhasil ditambahkan.';
        }

        $user->syncRoles($this->selectedRoles);

        if (in_array('doctor', $this->selectedRoles) && !empty($user->nik)) {
            if (empty($user->satusehat_id) || $user->wasChanged('nik')) {
                $result = $satuSehatService->getPractitionerByNik($user->nik);
                if ($result['success'] && isset($result['data']['id'])) {
                    $user->update(['satusehat_id' => $result['data']['id']]);
                    $message .= ' ID Satu Sehat berhasil disinkronisasi.';
                } else {
                    $message .= ' Namun gagal sinkronisasi Satu Sehat (NIK tidak ditemukan).';
                }
            }
        }

        $this->showForm = false;
        $this->reset(['editingId', 'name', 'email', 'password', 'phone', 'nik', 'selectedRoles']);
        session()->flash('success', $message);
    }

    public array $syncLogs = [];
    public bool $showSyncLogs = false;

    public function closeSyncLogs(): void
    {
        $this->showSyncLogs = false;
        $this->syncLogs = [];
    }

    public function syncSatuSehat(int $id, \App\Services\SatuSehatService $satuSehatService)
    {
        $user = User::findOrFail($id);
        
        if (!$user->hasRole('doctor')) {
            session()->flash('error', 'Fitur sinkronisasi Satu Sehat hanya diperuntukkan bagi Dokter.');
            return;
        }

        if (empty($user->nik)) {
            session()->flash('error', 'Dokter ini belum memiliki NIK. Pembaharuan data dokter di DTO Kemenkes membutuhkan NIK.');
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

    public function bulkSyncSatuSehat(\App\Services\SatuSehatService $satuSehatService): void
    {
        $this->syncLogs = [];
        
        // Find doctors with NIK but without Satu Sehat ID
        $doctors = User::role('doctor')
            ->where('clinic_id', auth()->user()->clinic_id)
            ->whereNotNull('nik')
            ->where('nik', '!=', '')
            ->whereNull('satusehat_id')
            ->limit(50)
            ->get();

        if ($doctors->isEmpty()) {
            session()->flash('success', 'Tidak ada data dokter (dengan NIK) yang perlu disinkronisasi.');
            return;
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($doctors as $doctor) {
            $result = $satuSehatService->getPractitionerByNik($doctor->nik);
            if ($result['success'] && isset($result['data']['id'])) {
                $doctor->update(['satusehat_id' => $result['data']['id']]);
                $successCount++;
                $this->syncLogs[] = [
                    'status' => 'success',
                    'message' => "Sinkronisasi berhasil untuk Dokter {$doctor->name} (NIK: {$doctor->nik})"
                ];
            } else {
                $failCount++;
                $errorMsg = is_string($result['error']) ? $result['error'] : 'Data tidak ditemukan di SISDMK';
                
                $this->syncLogs[] = [
                    'status' => 'error',
                    'message' => "Gagal sinkron Dokter {$doctor->name} (NIK: {$doctor->nik}) - Error: {$errorMsg}"
                ];
            }
        }

        $this->showSyncLogs = true;
        
        $message = "Proses sinkronisasi massal selesai. $successCount berhasil.";
        if ($failCount > 0) {
            $message .= " $failCount NIK tidak terdaftar di SISDMK.";
        }
        
        session()->flash('success', $message);
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
