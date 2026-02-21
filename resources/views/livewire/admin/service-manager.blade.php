<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen Jasa & Tindakan</x-slot:header>

<div>
    {{-- Flash --}}
    @if(session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-accent-500/10 border border-accent-500/20 text-accent-400 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Search & Add --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1 relative">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-surface-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari jasa / tindakan..."
                class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 shadow-sm rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
        </div>
        <button wire:click="openModal()"
            class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white text-sm font-medium rounded-xl hover:shadow-lg hover:-translate-y-0.5 shadow-md shadow-primary-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Jasa
        </button>
    </div>

    {{-- Services Table --}}
    <div class="rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-800/20">
                        <th class="px-6 py-3 font-semibold">Nama Tindakan / Jasa</th>
                        <th class="px-6 py-3 font-semibold text-right">Harga (Rp)</th>
                        <th class="px-6 py-3 font-semibold text-center">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($services as $service)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-surface-900 dark:text-surface-200">{{ $service->name }}</p>
                                    @if($service->is_automatic)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-primary-100 dark:bg-primary-500/20 text-primary-700 dark:text-primary-400 border border-primary-200 dark:border-primary-500/30">Auto</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-900 dark:text-surface-200 text-right font-medium">Rp
                                {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleActive({{ $service->id }})"
                                    class="px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide cursor-pointer transition-all border
                                    {{ $service->is_active ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 border-success-200/50 dark:border-transparent hover:bg-success-100 dark:hover:bg-success-500/20' : 'bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-400 border-surface-200/50 dark:border-transparent hover:bg-surface-200 dark:hover:bg-surface-700' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                    <button wire:click="openModal({{ $service->id }})"
                                        class="p-2 rounded-lg bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 hover:bg-surface-50 text-surface-500 dark:text-surface-400 hover:text-primary-600 shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $service->id }})"
                                        wire:confirm="Hapus jasa '{{ $service->name }}'?"
                                        class="p-2 rounded-lg bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 hover:bg-danger-50 text-surface-500 dark:text-surface-400 hover:text-danger-500 hover:border-danger-200 shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-surface-500">
                                Belum ada data tindakan medis/jasa. Klik "Tambah Jasa" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($services->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-transparent">
                {{ $services->links() }}
            </div>
        @endif
    </div>

    {{-- Add/Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 w-full max-w-md shadow-xl dark:shadow-2xl">
                <h3 class="text-xl font-bold text-surface-900 dark:text-white mb-6">{{ $editingId ? 'Edit Jasa/Tindakan' : 'Tambah Jasa Baru' }}</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Nama Jasa / Tindakan <span
                                class="text-danger-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Contoh: Cabut Gigi Anak"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all placeholder:text-surface-400 dark:placeholder:text-white/60">
                        @error('name') <p class="text-xs text-danger-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-white mb-1.5">Harga (Rp) <span
                                class="text-danger-500">*</span></label>
                        <input type="number" wire:model="price" min="0"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/20 rounded-xl shadow-sm text-surface-900 dark:text-white text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all placeholder:text-surface-400 dark:placeholder:text-white/60">
                        @error('price') <p class="text-xs text-danger-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-surface-200 dark:bg-surface-700 peer-focus:ring-2 peer-focus:ring-primary-500/50 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                        <span class="text-sm font-bold text-surface-700 dark:text-white">Jasa Aktif</span>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_automatic" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-surface-200 dark:bg-surface-700 peer-focus:ring-2 peer-focus:ring-primary-500/50 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                        <div>
                            <span class="block text-sm font-bold text-surface-700 dark:text-white">Otomatis Ditambahkan</span>
                            <span class="block text-xs font-semibold text-surface-500 dark:text-white/80">Jasa akan selalu disertakan dalam setiap tagihan pasien.</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button wire:click="$set('showModal', false)"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-white dark:bg-surface-800 text-surface-700 dark:text-white border border-surface-200 dark:border-white/20 shadow-sm hover:bg-surface-50 dark:hover:bg-surface-700 text-sm font-bold transition-all">
                        Batal
                    </button>
                    <button wire:click="save"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white text-sm font-bold hover:shadow-lg shadow-md hover:shadow-primary-500/30 shadow-primary-500/20 hover:-translate-y-0.5 transition-all">
                        {{ $editingId ? 'Simpan Perubahan' : 'Tambah Jasa' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
