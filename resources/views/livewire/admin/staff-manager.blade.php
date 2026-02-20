<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen User</x-slot:header>

<div>
    <div class="flex justify-end mb-6">
        <button wire:click="create"
            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40 transition-all">
            + Tambah User
        </button>
    </div>

    {{-- Add Staff Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="$set('showForm', false)">
            <div class="bg-surface-900 border border-white/10 rounded-2xl p-8 w-full max-w-md shadow-2xl">
                <h3 class="text-xl font-bold text-surface-100 mb-6">Tambah User Baru</h3>
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-300 mb-1">Nama *</label>
                        <input wire:model="name" type="text"
                            class="w-full px-4 py-2.5 bg-surface-800/50 border border-white/10 rounded-xl text-surface-100 focus:outline-none focus:border-primary-500 transition-all">
                        @error('name') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-300 mb-1">Email *</label>
                        <input wire:model="email" type="email"
                            class="w-full px-4 py-2.5 bg-surface-800/50 border border-white/10 rounded-xl text-surface-100 focus:outline-none focus:border-primary-500 transition-all">
                        @error('email') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-300 mb-1">Password *</label>
                        <input wire:model="password" type="password"
                            class="w-full px-4 py-2.5 bg-surface-800/50 border border-white/10 rounded-xl text-surface-100 focus:outline-none focus:border-primary-500 transition-all">
                        @error('password') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-300 mb-1">Telepon</label>
                        <input wire:model="phone" type="text"
                            class="w-full px-4 py-2.5 bg-surface-800/50 border border-white/10 rounded-xl text-surface-100 focus:outline-none focus:border-primary-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-300 mb-2">Role *</label>
                        @error('selectedRoles') <p class="text-xs text-danger-500 mb-2">{{ $message }}</p> @enderror
                        <div class="flex flex-wrap gap-3">
                            @php
                                $roleOptions = [
                                    'admin' => ['label' => 'Admin', 'color' => 'primary', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                                    'doctor' => ['label' => 'Dokter', 'color' => 'accent', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'],
                                    'cashier' => ['label' => 'Kasir', 'color' => 'warning', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                                ];
                            @endphp
                            @foreach($roleOptions as $roleValue => $roleInfo)
                                <label class="flex-1 min-w-[100px] cursor-pointer">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $roleValue }}"
                                        class="hidden peer">
                                    <div class="flex items-center gap-2 p-3 rounded-xl border-2 transition-all
                                                                        peer-checked:border-{{ $roleInfo['color'] }}-500 peer-checked:bg-{{ $roleInfo['color'] }}-500/10
                                                                        border-white/10 hover:border-white/20">
                                        <svg class="w-5 h-5 text-{{ $roleInfo['color'] }}-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">{!! $roleInfo['icon'] !!}</svg>
                                        <span class="text-sm font-medium text-surface-200">{{ $roleInfo['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-5 py-2.5 rounded-xl bg-surface-800 text-surface-300 border border-white/10 hover:bg-surface-700 transition-all">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold shadow-lg shadow-primary-500/20 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Roles Modal --}}
    @if($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="$set('showRoleModal', false)">
            <div class="bg-surface-900 border border-white/10 rounded-2xl p-8 w-full max-w-sm shadow-2xl">
                <h3 class="text-xl font-bold text-surface-100 mb-1">Atur Role</h3>
                <p class="text-sm text-surface-500 mb-6">{{ $roleUserName }}</p>
                @error('editRoles') <p class="text-xs text-danger-500 mb-3">{{ $message }}</p> @enderror

                <form wire:submit="saveRoles" class="space-y-3">
                    @php
                        $roleOptions = [
                            'admin' => ['label' => 'Admin', 'desc' => 'Kelola klinik, staf, dan pasien', 'color' => 'primary'],
                            'doctor' => ['label' => 'Dokter', 'desc' => 'Periksa pasien, rekam medis', 'color' => 'accent'],
                            'cashier' => ['label' => 'Kasir', 'desc' => 'Kelola billing dan pembayaran', 'color' => 'warning'],
                        ];
                    @endphp

                    @foreach($roleOptions as $roleValue => $roleInfo)
                        <label
                            class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                                                            {{ in_array($roleValue, $editRoles) ? 'border-' . $roleInfo['color'] . '-500 bg-' . $roleInfo['color'] . '-500/10' : 'border-white/10 hover:border-white/20' }}">
                            <input type="checkbox" wire:model.live="editRoles" value="{{ $roleValue }}"
                                class="w-5 h-5 rounded border-white/20 bg-surface-800 text-{{ $roleInfo['color'] }}-500 focus:ring-{{ $roleInfo['color'] }}-500/40 cursor-pointer">
                            <div>
                                <p class="text-sm font-semibold text-surface-200">{{ $roleInfo['label'] }}</p>
                                <p class="text-xs text-surface-500">{{ $roleInfo['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('showRoleModal', false)"
                            class="px-5 py-2.5 rounded-xl bg-surface-800 text-surface-300 border border-white/10 hover:bg-surface-700 transition-all">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold shadow-lg shadow-primary-500/20 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- User Table --}}
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-white/5">
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $member)
                        <tr class="hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-surface-200 font-medium">{{ $member->name }}</td>
                            <td class="px-6 py-4 text-sm text-surface-400">{{ $member->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($member->roles as $role)
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-primary-500/10 text-primary-400',
                                                'doctor' => 'bg-accent-500/10 text-accent-400',
                                                'cashier' => 'bg-warning-500/10 text-warning-500',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleColors[$role->name] ?? 'bg-surface-800 text-surface-400' }}">
                                            {{ ucfirst($role->name === 'doctor' ? 'Dokter' : ($role->name === 'cashier' ? 'Kasir' : $role->name)) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-accent-500/10 text-accent-400' : 'bg-danger-500/10 text-danger-500' }}">
                                    {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openRoleModal({{ $member->id }})"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-primary-500/10 text-primary-400 hover:bg-primary-500/20 transition-all">
                                        Atur Role
                                    </button>
                                    <button wire:click="toggleActive({{ $member->id }})"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ $member->is_active ? 'bg-danger-500/10 text-danger-500 hover:bg-danger-500/20' : 'bg-accent-500/10 text-accent-400 hover:bg-accent-500/20' }}">
                                        {{ $member->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-surface-500 text-sm">Belum ada staf lain.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>