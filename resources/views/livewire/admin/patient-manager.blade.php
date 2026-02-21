<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen Pasien</x-slot:header>

<div>
    <!-- Search + Add -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pasien..."
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 placeholder-surface-400 dark:placeholder-surface-600 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/40 transition-all">
        </div>
        <button wire:click="create"
            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40 transition-all whitespace-nowrap">
            + Tambah Pasien
        </button>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            wire:click.self="$set('showForm', false)">
            <div
                class="bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl dark:shadow-2xl">
                <h3 class="text-xl font-bold text-surface-900 dark:text-white mb-6">{{ $editingId ? 'Edit Pasien' : 'Tambah Pasien Baru' }}
                </h3>
                <form wire:submit="save" class="space-y-4">
                    <!-- Photo Upload -->
                    <div class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-surface-200 dark:border-white/10 rounded-2xl bg-surface-50 dark:bg-surface-800/30">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-surface-200 dark:bg-surface-700 border-2 border-primary-500/20">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif ($existingPhoto)
                                    <img src="{{ asset('storage/' . $existingPhoto) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-surface-400 dark:text-surface-500">
                                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                                <div wire:loading wire:target="photo" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            <label class="absolute bottom-0 right-0 p-1.5 bg-primary-600 rounded-full cursor-pointer shadow-lg hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" wire:model="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <p class="mt-2 text-[10px] text-surface-500 uppercase tracking-wider font-semibold">Foto Pasien (Opsional)</p>
                        @error('photo') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Nama *</label>
                        <input wire:model="name" type="text"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                        @error('name') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">NIK</label>
                            <input wire:model="nik" type="text" maxlength="16"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                            @error('nik') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">No. Rekam Medis</label>
                            <input wire:model="medical_record_no" type="text"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Nama Ibu Kandung</label>
                            <input wire:model="mother_name" type="text"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                            @error('mother_name') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">NIK Ibu Kandung</label>
                            <input wire:model="mother_nik" type="text" maxlength="16"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                            @error('mother_nik') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Tanggal Lahir</label>
                            <input wire:model="birth_date" type="date"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Jenis Kelamin</label>
                            <select wire:model="gender"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                                <option value="">Pilih</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Telepon</label>
                            <input wire:model="phone" type="text"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Golongan Darah</label>
                            <select wire:model="blood_type"
                                class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all">
                                <option value="">Pilih</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Alamat</label>
                        <textarea wire:model="address" rows="2"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 transition-all resize-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-5 py-2.5 rounded-xl bg-white dark:bg-surface-800 text-surface-700 dark:text-white border border-surface-200 dark:border-white/20 hover:bg-surface-50 dark:hover:bg-surface-700 shadow-sm text-sm font-bold transition-all">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white text-sm font-bold hover:shadow-lg shadow-md hover:shadow-primary-500/30 shadow-primary-500/20 hover:-translate-y-0.5 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-800/20">
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">NIK</th>
                        <th class="px-6 py-4 font-semibold">No. RM</th>
                        <th class="px-6 py-4 font-semibold">Telepon</th>
                        <th class="px-6 py-4 font-semibold">JK</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-100 dark:bg-surface-700 border border-surface-200 dark:border-white/5 flex-shrink-0">
                                        @if($patient->photo_path)
                                            <img src="{{ asset('storage/' . $patient->photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-surface-500 text-xs font-bold bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400">
                                                {{ strtoupper(substr($patient->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-sm text-surface-900 dark:text-surface-200 font-medium">{{ $patient->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-sm text-surface-600 dark:text-surface-400">{{ $patient->nik ?? '-' }}</span>
                                    @if($patient->nik)
                                        @if($patient->satu_sehat_patient_id)
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400 border border-success-200 dark:border-success-500/20">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Satu Sehat: {{ $patient->satu_sehat_patient_id }}
                                            </span>
                                        @else
                                            <button wire:click="syncSatuSehat({{ $patient->id }})" wire:loading.attr="disabled"
                                                class="inline-flex w-fit items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-400 border border-surface-200 dark:border-white/10 hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-500/10 dark:hover:text-primary-400 transition-colors disabled:opacity-50">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span wire:loading.remove wire:target="syncSatuSehat({{ $patient->id }})">Sinkron NIK</span>
                                                <span wire:loading wire:target="syncSatuSehat({{ $patient->id }})">Loading...</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">{{ $patient->medical_record_no ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">{{ $patient->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">
                                @if($patient->gender === 'male')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200/50 dark:border-transparent">L</span>
                                @elseif($patient->gender === 'female')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-50 dark:bg-pink-500/10 text-pink-700 dark:text-pink-400 border border-pink-200/50 dark:border-transparent">P</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button wire:click="edit({{ $patient->id }})"
                                    class="p-2 rounded-lg bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 hover:bg-surface-50 text-surface-500 dark:text-surface-400 hover:text-primary-600 shadow-sm transition-all mr-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $patient->id }})"
                                    wire:confirm="Yakin ingin menghapus pasien ini?"
                                    class="p-2 rounded-lg bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 hover:bg-danger-50 text-surface-500 dark:text-surface-400 hover:text-danger-500 hover:border-danger-200 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-surface-500 text-sm">
                                @if($search)
                                    Tidak ada pasien ditemukan untuk "{{ $search }}".
                                @else
                                    Belum ada data pasien. Klik <span class="text-primary-600 dark:text-primary-400 font-medium">"Tambah Pasien"</span> untuk
                                    memulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-transparent">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>