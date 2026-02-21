<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen User</x-slot:header>

<div>
    <div class="flex justify-end mb-6">
        <button wire:click="create"
            class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all">
            + Tambah User
        </button>
    </div>

    {{-- Add Staff Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="$set('showForm', false)">
            <div class="bg-white/90 dark:bg-surface-900/90 backdrop-blur-2xl border border-white dark:border-white/10 rounded-3xl p-8 w-full max-w-md shadow-2xl shadow-surface-300/40 dark:shadow-none ring-1 ring-surface-200/50 dark:ring-0">
                <h3 class="text-xl font-bold text-surface-900 dark:text-surface-100 mb-6">Tambah User Baru</h3>
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Nama *</label>
                        <input wire:model="name" type="text"
                            class="w-full px-4 py-3 bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200/60 dark:border-white/10 rounded-2xl text-surface-900 dark:text-surface-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        @error('name') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Email *</label>
                        <input wire:model="email" type="email"
                            class="w-full px-4 py-3 bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200/60 dark:border-white/10 rounded-2xl text-surface-900 dark:text-surface-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        @error('email') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Password *</label>
                        <input wire:model="password" type="password"
                            class="w-full px-4 py-3 bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200/60 dark:border-white/10 rounded-2xl text-surface-900 dark:text-surface-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        @error('password') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Telepon</label>
                        <input wire:model="phone" type="text"
                            class="w-full px-4 py-3 bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200/60 dark:border-white/10 rounded-2xl text-surface-900 dark:text-surface-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Role *</label>
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
                                    <div class="flex items-center gap-2 p-3 rounded-2xl border-2 transition-all hover:-translate-y-0.5
                                                                        peer-checked:border-{{ $roleInfo['color'] }}-500 peer-checked:bg-{{ $roleInfo['color'] }}-500/5 dark:peer-checked:bg-{{ $roleInfo['color'] }}-500/10 peer-checked:shadow-sm
                                                                        border-surface-200/60 dark:border-white/10 hover:border-surface-300 dark:hover:border-white/20 bg-surface-50/30 dark:bg-transparent">
                                        <svg class="w-5 h-5 text-{{ $roleInfo['color'] }}-500 dark:text-{{ $roleInfo['color'] }}-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">{!! $roleInfo['icon'] !!}</svg>
                                        <span class="text-sm font-medium text-surface-900 dark:text-surface-200">{{ $roleInfo['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-5 py-2.5 rounded-2xl bg-surface-100/50 dark:bg-surface-800 text-surface-600 dark:text-surface-300 border border-surface-200/50 dark:border-white/10 hover:bg-surface-200/50 dark:hover:bg-surface-700 transition-all">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Roles Modal --}}
    @if($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:click.self="$set('showRoleModal', false)">
            <div class="bg-white/90 dark:bg-surface-900/90 backdrop-blur-2xl border border-white dark:border-white/10 rounded-3xl p-8 w-full max-w-sm shadow-2xl shadow-surface-300/40 dark:shadow-none ring-1 ring-surface-200/50 dark:ring-0">
                <h3 class="text-xl font-bold text-surface-900 dark:text-surface-100 mb-1">Atur Role</h3>
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
                            class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:-translate-y-0.5 bg-surface-50/30 dark:bg-transparent
                                                            {{ in_array($roleValue, $editRoles) ? 'border-' . $roleInfo['color'] . '-500 bg-' . $roleInfo['color'] . '-500/5 dark:bg-' . $roleInfo['color'] . '-500/10 shadow-sm' : 'border-surface-200/60 dark:border-white/10 hover:border-surface-300 dark:hover:border-white/20' }}">
                            <input type="checkbox" wire:model.live="editRoles" value="{{ $roleValue }}"
                                class="w-5 h-5 rounded border-surface-300 dark:border-white/20 bg-surface-50 dark:bg-surface-800 text-{{ $roleInfo['color'] }}-500 focus:ring-{{ $roleInfo['color'] }}-500/40 cursor-pointer">
                            <div>
                                <p class="text-sm font-semibold text-surface-900 dark:text-surface-200">{{ $roleInfo['label'] }}</p>
                                <p class="text-xs text-surface-600 dark:text-surface-500">{{ $roleInfo['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach

                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" wire:click="$set('showRoleModal', false)"
                            class="px-5 py-2.5 rounded-2xl bg-surface-100/50 dark:bg-surface-800 text-surface-600 dark:text-surface-300 border border-surface-200/50 dark:border-white/10 hover:bg-surface-200/50 dark:hover:bg-surface-700 transition-all">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- User Table --}}
    <div class="rounded-3xl bg-white/70 dark:bg-surface-900/60 backdrop-blur-xl shadow-xl shadow-surface-200/40 dark:shadow-none border border-white dark:border-white/5 ring-1 ring-surface-200/50 dark:ring-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs bg-surface-100/50 dark:bg-transparent text-surface-500 dark:text-surface-400 uppercase tracking-wider font-semibold border-b border-surface-200/50 dark:border-white/5">
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($users as $member)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-surface-900 dark:text-surface-200 font-medium">{{ $member->name }}</td>
                            <td class="px-6 py-4 text-sm text-surface-500 dark:text-surface-400">{{ $member->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($member->roles as $role)
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 ring-1 ring-inset ring-primary-500/20',
                                                'doctor' => 'bg-accent-50 dark:bg-accent-500/10 text-accent-700 dark:text-accent-400 ring-1 ring-inset ring-accent-500/20',
                                                'cashier' => 'bg-warning-50 dark:bg-warning-500/10 text-warning-700 dark:text-warning-400 ring-1 ring-inset ring-warning-500/20',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleColors[$role->name] ?? 'bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-400' }}">
                                            {{ ucfirst($role->name === 'doctor' ? 'Dokter' : ($role->name === 'cashier' ? 'Kasir' : $role->name)) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-1 ring-inset ring-success-500/20' : 'bg-danger-50 dark:bg-danger-500/10 text-danger-700 dark:text-danger-400 ring-1 ring-inset ring-danger-500/20' }}">
                                    {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openRoleModal({{ $member->id }})"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-surface-100 dark:bg-primary-500/10 text-surface-600 dark:text-primary-400 border border-surface-200 dark:border-transparent hover:bg-surface-200 dark:hover:bg-primary-500/20 transition-all">
                                        Atur Role
                                    </button>
                                    <button wire:click="toggleActive({{ $member->id }})"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ $member->is_active ? 'bg-danger-50 dark:bg-danger-500/10 text-danger-600 dark:text-danger-500 border border-danger-200 dark:border-transparent hover:bg-danger-100 dark:hover:bg-danger-500/20' : 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 border border-success-200 dark:border-transparent hover:bg-success-100 dark:hover:bg-success-500/20' }}">
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