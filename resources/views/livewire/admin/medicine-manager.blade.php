<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen Obat</x-slot:header>

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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari obat..."
                class="w-full pl-10 pr-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
        </div>
        <select wire:model.live="filterCategory"
            class="px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
            @endforeach
        </select>
        <button wire:click="openModal()"
            class="px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-500 shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Obat
        </button>
    </div>

    {{-- Medicines Table --}}
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-white/5">
                        <th class="px-6 py-3 font-medium">Nama Obat</th>
                        <th class="px-6 py-3 font-medium">Kategori</th>
                        <th class="px-6 py-3 font-medium">Unit</th>
                        <th class="px-6 py-3 font-medium text-right">Harga</th>
                        <th class="px-6 py-3 font-medium text-center">Stok</th>
                        <th class="px-6 py-3 font-medium text-center">Status</th>
                        <th class="px-6 py-3 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($medicines as $medicine)
                        <tr class="hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-3">
                                <p class="text-sm font-medium text-surface-200">{{ $medicine->name }}</p>
                                @if($medicine->generic_name)
                                    <p class="text-xs text-surface-500">{{ $medicine->generic_name }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <span
                                    class="px-2.5 py-1 rounded-full text-xs font-medium bg-primary-500/10 text-primary-400">{{ ucfirst($medicine->category) }}</span>
                            </td>
                            <td class="px-6 py-3 text-sm text-surface-400">{{ $medicine->unit }}</td>
                            <td class="px-6 py-3 text-sm text-surface-200 text-right font-medium">Rp
                                {{ number_format($medicine->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm text-center">
                                <span
                                    class="{{ $medicine->stock <= 5 ? 'text-danger-500 font-bold' : 'text-surface-300' }}">{{ $medicine->stock }}</span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <button wire:click="toggleActive({{ $medicine->id }})"
                                    class="px-2.5 py-1 rounded-full text-xs font-medium cursor-pointer transition-all
                                    {{ $medicine->is_active ? 'bg-accent-500/10 text-accent-400 hover:bg-accent-500/20' : 'bg-surface-800 text-surface-500 hover:bg-surface-700' }}">
                                    {{ $medicine->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openModal({{ $medicine->id }})"
                                        class="p-1.5 rounded-lg hover:bg-surface-700 text-surface-400 hover:text-primary-400 transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $medicine->id }})"
                                        wire:confirm="Hapus obat '{{ $medicine->name }}'?"
                                        class="p-1.5 rounded-lg hover:bg-surface-700 text-surface-400 hover:text-danger-500 transition-all">
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
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-surface-500">
                                Belum ada data obat. Klik "Tambah Obat" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($medicines->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $medicines->links() }}
            </div>
        @endif
    </div>

    {{-- Add/Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-surface-900 border border-white/10 rounded-2xl p-6 w-full max-w-lg shadow-2xl">
                <h3 class="text-lg font-bold text-surface-100 mb-4">{{ $editingId ? 'Edit Obat' : 'Tambah Obat Baru' }}</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Nama Obat <span
                                class="text-danger-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Contoh: Paracetamol 500mg"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
                        @error('name') <p class="text-xs text-danger-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Nama Generik</label>
                        <input type="text" wire:model="generic_name" placeholder="Contoh: Acetaminophen"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-surface-400 mb-1.5">Kategori</label>
                            <select wire:model="category"
                                class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-400 mb-1.5">Satuan</label>
                            <input type="text" wire:model="unit" placeholder="pcs, strip, botol..."
                                class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-surface-400 mb-1.5">Harga (Rp) <span
                                    class="text-danger-500">*</span></label>
                            <input type="number" wire:model="price" min="0"
                                class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
                            @error('price') <p class="text-xs text-danger-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-400 mb-1.5">Stok</label>
                            <input type="number" wire:model="stock" min="0"
                                class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-surface-700 peer-focus:ring-2 peer-focus:ring-primary-500/50 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                        <span class="text-sm text-surface-300">Aktif</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="$set('showModal', false)"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-surface-800 text-surface-300 border border-white/10 hover:bg-surface-700 text-sm font-medium transition-all">
                        Batal
                    </button>
                    <button wire:click="save"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-500 shadow-lg shadow-primary-500/20 transition-all">
                        {{ $editingId ? 'Simpan' : 'Tambah' }}
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>