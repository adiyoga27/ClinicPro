<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>
    <div class="flex items-center gap-3">
        <div class="p-2 rounded-xl bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-surface-900 dark:text-white">Pengaturan Satu Sehat</h2>
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Konfigurasi koneksi API Satu Sehat untuk klinik ini.</p>
        </div>
    </div>
</x-slot:header>

<div class="max-w-3xl">
    <div class="bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/10 rounded-2xl p-6 md:p-8 shadow-sm dark:shadow-none">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-500/20 text-success-700 dark:text-success-400 flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-surface-900 dark:text-surface-200 mb-1.5">Organization ID</label>
                    <p class="text-xs font-medium text-surface-500 dark:text-surface-400 mb-2">ID Fasilitas Kesehatan Anda yang didapatkan dari DTO Kemenkes.</p>
                    <input type="text" wire:model="satusehat_organization_id" placeholder="Misal: 1000..." 
                        class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-medium placeholder-surface-400 dark:placeholder-surface-500">
                    @error('satusehat_organization_id') <p class="text-xs font-bold text-danger-600 dark:text-danger-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-surface-900 dark:text-surface-200 mb-1.5">Client ID</label>
                    <input type="text" wire:model="satusehat_client_id" placeholder="Client ID Aplikasi..." 
                        class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-medium placeholder-surface-400 dark:placeholder-surface-500">
                    @error('satusehat_client_id') <p class="text-xs font-bold text-danger-600 dark:text-danger-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-surface-900 dark:text-surface-200 mb-1.5">Client Secret</label>
                    <input type="password" wire:model="satusehat_client_secret" placeholder="Client Secret Aplikasi..." 
                        class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-medium placeholder-surface-400 dark:placeholder-surface-500">
                    @error('satusehat_client_secret') <p class="text-xs font-bold text-danger-600 dark:text-danger-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-surface-200 dark:border-white/10 flex justify-end">
                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white font-bold hover:bg-primary-500 shadow-sm shadow-primary-500/20 transition-all text-sm disabled:opacity-50 flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
