<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Deposit Pasien</x-slot:header>

<div class="space-y-6">
    <!-- Search -->
    <div class="relative max-w-md">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pasien (Nama/NIK/RM)..."
            class="w-full pl-10 pr-4 py-3 bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 placeholder-surface-400 dark:placeholder-surface-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
    </div>

    <!-- Patient Table -->
    <div class="rounded-2xl bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 overflow-hidden shadow-sm dark:shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-950/50">
                        <th class="px-6 py-4 font-bold">Pasien</th>
                        <th class="px-6 py-4 font-bold">No. RM</th>
                        <th class="px-6 py-4 font-bold">Saldo Deposit</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold ring-1 ring-primary-100 dark:ring-surface-800">
                                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm text-surface-900 dark:text-surface-200 font-bold">{{ $patient->name }}</div>
                                        <div class="text-xs text-surface-500 dark:text-surface-400 font-medium">{{ $patient->nik ?: '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400 font-mono font-medium">{{ $patient->medical_record_no }}</td>
                            <td class="px-6 py-4 text-sm font-black text-primary-600 dark:text-primary-400">
                                Rp {{ number_format($patient->deposit_balance, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="selectPatient({{ $patient->id }})"
                                    class="px-4 py-2 bg-surface-100/50 dark:bg-surface-800 text-primary-600 dark:text-primary-400 border border-surface-200 dark:border-primary-500/20 rounded-lg text-xs font-bold hover:bg-primary-600 dark:hover:bg-primary-500 hover:text-white dark:hover:text-white hover:border-transparent transition-all shadow-sm hover:shadow-lg hover:shadow-primary-500/20 dark:hover:shadow-primary-500/5 hover:-translate-y-0.5">
                                    Kelola Deposit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-surface-600">
                                Tidak ada data pasien ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-900/40">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

    <!-- Deposit Modal -->
    @if($showModal && $selectedPatient)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-surface-900/40 dark:bg-black/60 backdrop-blur-sm px-4">
            <div class="bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
                <!-- Modal Header -->
                <div class="p-6 border-b border-surface-200 dark:border-white/5 flex items-center justify-between bg-surface-50 dark:bg-transparent">
                    <div>
                        <h3 class="text-xl font-bold text-surface-900 dark:text-white">Kelola Deposit</h3>
                        <p class="text-sm font-medium text-surface-600 dark:text-surface-500">{{ $selectedPatient->name }} (RM: {{ $selectedPatient->medical_record_no }})</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-surface-400 dark:text-surface-500 hover:text-surface-600 dark:hover:text-white transition-colors bg-white dark:bg-transparent p-1 rounded-lg border border-surface-200 dark:border-transparent">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-8 font-outfit">
                    <!-- Top Up Form -->
                    <div class="space-y-6">
                        <div class="p-6 rounded-2xl bg-surface-50 dark:bg-surface-950/50 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none">
                            <div class="text-xs text-primary-600 dark:text-primary-400 uppercase tracking-widest font-black mb-1">Saldo Saat Ini</div>
                            <div class="text-3xl font-extrabold text-surface-900 dark:text-white">Rp {{ number_format($selectedPatient->deposit_balance, 0, ',', '.') }}</div>
                        </div>

                        <form wire:submit="processTransaction" class="space-y-4">
                            @if (session()->has('success'))
                                <div class="p-4 bg-success-500/10 border border-success-500/20 text-success-400 rounded-xl text-sm font-medium">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-bold text-surface-700 dark:text-surface-400 mb-2">Tipe Transaksi</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" wire:click="$set('type', 'topup')"
                                        class="py-2.5 px-4 rounded-xl text-sm font-bold border transition-all {{ $type === 'topup' ? 'bg-primary-50 dark:bg-primary-600/20 border-primary-200 dark:border-primary-500 text-primary-700 dark:text-primary-400 shadow-sm' : 'bg-surface-50 dark:bg-surface-800 border-surface-200 dark:border-white/5 text-surface-600 dark:text-surface-500 hover:border-surface-300 dark:hover:border-white/10' }}">
                                        Top Up (+)
                                    </button>
                                    <button type="button" wire:click="$set('type', 'usage')"
                                        class="py-2.5 px-4 rounded-xl text-sm font-bold border transition-all {{ $type === 'usage' ? 'bg-danger-50 dark:bg-danger-600/20 border-danger-200 dark:border-danger-500 text-danger-700 dark:text-danger-400 shadow-sm' : 'bg-surface-50 dark:bg-surface-800 border-surface-200 dark:border-white/5 text-surface-600 dark:text-surface-500 hover:border-surface-300 dark:hover:border-white/10' }}">
                                        Pencairan (-)
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-surface-700 dark:text-surface-400 mb-2">Jumlah (Rp)</label>
                                <input wire:model="amount" type="number" step="1"
                                    class="w-full px-4 py-3 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-100 placeholder-surface-400 dark:placeholder-surface-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-xl font-bold transition-all shadow-sm dark:shadow-none">
                                @error('amount') <span class="text-xs font-bold text-danger-600 dark:text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-surface-700 dark:text-surface-400 mb-2">Keterangan</label>
                                <textarea wire:model="description" rows="2" placeholder="Contoh: Top up mandiri, Biaya admin, dll"
                                    class="w-full px-4 py-3 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-100 placeholder-surface-400 dark:placeholder-surface-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-sm font-medium transition-all resize-none shadow-sm dark:shadow-none"></textarea>
                                @error('description') <span class="text-xs font-bold text-danger-600 dark:text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-black uppercase tracking-widest rounded-xl shadow-xl shadow-primary-500/20 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all">
                                Proses Transaksi
                            </button>
                        </form>
                    </div>

                    <!-- Transaction History -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-black text-surface-500 dark:text-surface-400 uppercase tracking-widest px-2">Riwayat Terakhir</h4>
                        <div class="space-y-3 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                            @forelse($selectedPatient->depositTransactions as $trx)
                                <div class="p-4 rounded-xl bg-surface-50/50 dark:bg-surface-800/40 border border-surface-200 dark:border-white/5 flex items-center justify-between group hover:bg-surface-100/50 dark:hover:bg-surface-800/60 transition-all shadow-sm dark:shadow-none">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $trx->type === 'topup' ? 'bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400 border border-success-200 dark:border-success-500/20' : 'bg-danger-50 dark:bg-danger-500/10 text-danger-600 dark:text-danger-400 border border-danger-200 dark:border-danger-500/20' }}">
                                            @if($trx->type === 'topup')
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $trx->description }}</div>
                                            <div class="text-[10px] text-surface-500 uppercase font-bold">{{ $trx->created_at->format('d/m/Y H:i') }} • {{ $trx->user->name }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-black {{ $trx->type === 'topup' ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                                            {{ $trx->type === 'topup' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-surface-600 text-sm italic font-medium">Belum ada riwayat transaksi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
