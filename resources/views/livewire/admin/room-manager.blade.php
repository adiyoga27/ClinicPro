<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen Poli / Ruangan</x-slot:header>

<div>
    <div class="flex justify-end gap-3 mb-6">
        <button wire:click="pullFromSatuSehat" wire:loading.attr="disabled"
            class="px-6 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 text-surface-700 dark:text-surface-100 font-medium rounded-2xl shadow-sm hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span wire:loading.remove wire:target="pullFromSatuSehat">Tarik Data dari Satu Sehat</span>
            <span wire:loading wire:target="pullFromSatuSehat">Status Sinkronisasi...</span>
        </button>
        <button wire:click="create"
            class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Ruangan
        </button>
    </div>

    <!-- Modal Form -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            wire:click.self="$set('showForm', false)">
            <div class="bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 w-full max-w-md shadow-xl dark:shadow-2xl">
                <h3 class="text-xl font-bold text-surface-900 dark:text-white mb-6">
                    {{ $editingId ? 'Edit Ruangan' : 'Tambah Ruangan Baru' }}
                </h3>
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-100 mb-1.5">Nama Poli / Ruangan *</label>
                        <input wire:model="name" type="text" placeholder="Misal: Poli Umum, Poli Gigi"
                            class="w-full px-4 py-2.5 bg-white dark:bg-surface-800/50 border border-surface-200 dark:border-white/10 rounded-xl shadow-sm text-surface-900 dark:text-surface-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        @error('name') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-5 py-2.5 rounded-xl bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-100 border border-surface-200 dark:border-white/10 shadow-sm hover:bg-surface-50 dark:hover:bg-surface-700 transition-all text-sm font-bold">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-bold text-sm shadow-md shadow-primary-500/20 hover:shadow-lg hover:shadow-primary-500/30 hover:-translate-y-0.5 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs bg-surface-50/50 dark:bg-transparent text-surface-500 dark:text-surface-400 uppercase tracking-wider font-semibold border-b border-surface-200 dark:border-white/5">
                        <th class="px-6 py-4">Nama Ruangan</th>
                        <th class="px-6 py-4">Status Satu Sehat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($rooms as $room)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-surface-900 dark:text-surface-200 font-medium">{{ $room->name }}</td>
                            <td class="px-6 py-4">
                                @if($room->satusehat_id)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-bold bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400 border border-success-200 dark:border-success-500/20">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Disinkronkan: {{ $room->satusehat_id }}
                                    </span>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-medium text-surface-500 dark:text-surface-400">Belum disinkronkan</span>
                                        <button wire:click="syncSatuSehat({{ $room->id }})" wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-100 border border-surface-200 dark:border-white/10 hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-500/10 dark:hover:text-primary-400 transition-colors shadow-sm disabled:opacity-50">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span wire:loading.remove wire:target="syncSatuSehat({{ $room->id }})">Upload ke Kemenkes</span>
                                            <span wire:loading wire:target="syncSatuSehat({{ $room->id }})">Loading...</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $room->id }})"
                                    class="p-2 rounded-lg bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 hover:bg-surface-50 text-surface-500 dark:text-surface-400 hover:text-primary-600 shadow-sm transition-all mr-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <button wire:click="delete({{ $room->id }})"
                                    wire:confirm="Yakin ingin menghapus ruangan ini?"
                                    class="p-2 rounded-lg bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 hover:bg-danger-50 text-surface-500 dark:text-surface-400 hover:text-danger-500 hover:border-danger-200 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-surface-500 text-sm">
                                Belum ada data ruangan / poli.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
