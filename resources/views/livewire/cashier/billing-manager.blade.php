<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Kasir & Pembayaran</x-slot:header>

<div class="space-y-6">
    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row gap-3">
        <!-- Search -->
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / no. RM..."
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 placeholder-surface-400 dark:placeholder-surface-600 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/40 transition-all">
        </div>

        <!-- Filter Tabs -->
        <div class="flex p-1 bg-white dark:bg-surface-900/60 rounded-xl shadow-sm dark:shadow-none border border-surface-200 dark:border-white/10 overflow-x-auto">
            @foreach(['all' => 'Semua', 'unpaid' => 'Belum Bayar', 'paid' => 'Lunas'] as $key => $label)
                <button wire:click="$set('statusFilter', '{{ $key }}')"
                    class="flex-1 sm:flex-none min-w-[100px] px-5 py-2 rounded-lg text-sm font-semibold transition-all {{ $statusFilter === $key ? 'bg-primary-50 dark:bg-primary-500/20 text-primary-600 dark:text-primary-400 shadow-sm ring-1 ring-primary-500/20 dark:ring-white/10' : 'text-surface-500 dark:text-surface-400 hover:text-surface-900 dark:hover:text-surface-200 hover:bg-surface-50 dark:hover:bg-surface-800/50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Billings Table Card -->
    <div class="bg-white dark:bg-surface-900/40 rounded-3xl shadow-sm border border-surface-200 dark:border-white/5 overflow-hidden backdrop-blur-2xl">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-surface-50/50 dark:bg-surface-950/50 border-b border-surface-200 dark:border-white/5">
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider">ID Tagihan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider text-right">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($billings as $billing)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group relative">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-sm ring-1 dark:ring-2 ring-primary-100 dark:ring-surface-800 shadow-sm">
                                        {{ strtoupper(substr($billing->patient->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-surface-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $billing->patient->name }}
                                        </p>
                                        <p class="text-xs text-surface-500 dark:text-surface-400 font-medium">RM: {{ $billing->patient->medical_record_no }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-surface-100 dark:bg-surface-900/60 text-surface-600 dark:text-surface-300 ring-1 ring-inset ring-surface-200 dark:ring-white/10">
                                    #{{ $billing->id }}
                                </span>
                                <div class="text-[10px] text-surface-500 mt-1 font-medium">{{ $billing->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                @if($billing->status === 'paid')
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-200 dark:ring-emerald-500/20 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Lunas
                                        </span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($billing->payments as $payment)
                                                <span class="text-[9px] font-bold text-surface-500 uppercase tracking-wider">{{ $payment->payment_method }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-200 dark:ring-amber-500/20 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-sm font-bold text-surface-900 dark:text-white">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                @if($billing->status === 'unpaid')
                                    <button wire:click="selectBilling({{ $billing->id }})"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white transition-all bg-gradient-to-r from-primary-600 to-primary-500 rounded-xl hover:shadow-lg shadow-md focus:outline-none hover:-translate-y-0.5 hover:shadow-primary-500/30 shadow-primary-500/20 active:scale-95 whitespace-nowrap">
                                        Bayar Tagihan
                                        <svg class="w-4 h-4 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                @else
                                    <button disabled
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-surface-400 dark:text-surface-500 bg-surface-100 dark:bg-surface-800/50 rounded-xl cursor-not-allowed border border-surface-200 dark:border-white/5 whitespace-nowrap">
                                        Tuntas
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-surface-600">
                                Tidak ada tagihan pending saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($billings->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-transparent">
                {{ $billings->links() }}
            </div>
        @endif
    </div>

    <!-- Payment Modal (Glassmorphism) -->
    @if($showModal && $selectedBilling)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-md" wire:click="$set('showModal', false)"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-surface-900/90 dark:backdrop-blur-2xl border border-surface-200 dark:border-white/10 rounded-3xl w-full max-w-4xl max-h-[95vh] sm:max-h-[90vh] overflow-hidden flex flex-col shadow-2xl ring-1 ring-black/5">
                
                <!-- Header -->
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-surface-200 dark:border-white/10 flex items-start sm:items-center justify-between bg-surface-50 dark:bg-surface-900/50 gap-4">
                    <div class="space-y-1">
                        <h3 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white tracking-tight">Proses Pembayaran</h3>
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-surface-500 dark:text-surface-400">
                            <span class="font-medium text-surface-700 dark:text-surface-300">{{ $selectedBilling->patient->name }}</span>
                            <span class="w-1 h-1 bg-surface-300 dark:bg-surface-600 rounded-full hidden sm:block"></span>
                            <span>RM: {{ $selectedBilling->patient->medical_record_no }}</span>
                        </div>
                    </div>
                    <button wire:click="$set('showModal', false)" class="p-2 bg-white dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 text-surface-500 dark:text-surface-400 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500/50 shadow-sm border border-surface-200 dark:border-transparent">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 flex flex-col lg:grid lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-12">
                    <!-- Billing Details -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Invoice Items -->
                        <div class="bg-surface-50 dark:bg-surface-950/50 rounded-2xl p-4 sm:p-6 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none">
                            <h4 class="text-xs font-bold text-surface-500 uppercase tracking-widest mb-4">Rincian Tagihan</h4>
                            <div class="space-y-3 relative">
                                @foreach($selectedBilling->items as $item)
                                    <div class="flex flex-col sm:flex-row justify-between text-sm group gap-1 sm:gap-0">
                                        <span class="text-surface-700 dark:text-surface-300">{{ $item->name }} <span class="text-surface-500 text-xs ml-1 sm:ml-2 font-semibold">x{{ $item->qty }}</span></span>
                                        <span class="font-bold text-surface-900 dark:text-white whitespace-nowrap">Rp {{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                <div class="pt-4 mt-4 border-t border-surface-200 dark:border-white/10 flex justify-between items-center">
                                    <span class="font-bold text-surface-900 dark:text-white">Total Tagihan</span>
                                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($selectedBilling->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Balances -->
                        <div class="bg-primary-50 dark:bg-primary-500/5 rounded-2xl p-5 border border-primary-200 dark:border-primary-500/20 space-y-3 relative overflow-hidden">
                            <!-- Decorative element -->
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary-100 dark:bg-primary-500/10 rounded-full blur-2xl pointer-events-none"></div>
                            
                            <div class="flex items-center justify-between relative z-10">
                                <span class="text-xs font-bold text-primary-600/80 dark:text-primary-400/80 uppercase tracking-wider">Saldo Deposit Pasien</span>
                                <span class="font-bold text-primary-700 dark:text-primary-300">Rp {{ number_format($selectedBilling->patient->deposit_balance, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="pt-3 border-t border-primary-200/50 dark:border-primary-500/20 flex items-center justify-between relative z-10">
                                <span class="text-xs font-bold text-primary-600/80 dark:text-primary-400/80 uppercase tracking-wider">Sisa Pembayaran</span>
                                <span class="text-lg font-bold {{ abs($this->totalPaid - $selectedBilling->total_amount) < 0.01 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    Rp {{ number_format($selectedBilling->total_amount - $this->totalPaid, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-surface-500 uppercase tracking-widest">Metode Pembayaran</h4>
                            <button type="button" wire:click="addPaymentRow" class="text-xs font-bold text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 flex items-center gap-1 transition-colors px-3 py-1.5 rounded-full hover:bg-primary-50 dark:hover:bg-primary-500/10">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Split Bill
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($paymentRows as $index => $row)
                                <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-surface-800/40 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none relative group transition-all duration-300 hover:border-primary-300 dark:hover:border-primary-500/30">
                                    @if(count($paymentRows) > 1)
                                        <button wire:click="removePaymentRow({{ $index }})" class="absolute -top-3 -right-3 z-10 p-1.5 text-surface-500 hover:text-danger-500 bg-white dark:bg-surface-900 rounded-full shadow-sm ring-1 ring-surface-200 dark:ring-white/10 transition-all sm:opacity-0 sm:group-hover:opacity-100 scale-100 sm:scale-90 sm:group-hover:scale-100">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-surface-600 dark:text-surface-500 mb-1.5">Metode</label>
                                            <select wire:model.live="paymentRows.{{ $index }}.method" 
                                                class="w-full pl-3 pr-8 py-2.5 bg-surface-50 dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white font-medium focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                                                <option value="cash">💵 Cash / Tunai</option>
                                                <option value="debit">💳 Debit / Kartu</option>
                                                <option value="qris">📱 QRIS</option>
                                                <option value="bpjs">🏥 BPJS Kesehatan</option>
                                                <option value="deposit">💰 Potong Deposit</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-surface-600 dark:text-surface-500 mb-1.5">Nominal (Rp)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-surface-500 text-sm font-bold">Rp</span>
                                                </div>
                                                <input wire:model.live="paymentRows.{{ $index }}.amount" type="number"
                                                    class="w-full pl-9 pr-3 py-2.5 bg-surface-50 dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white font-bold focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('totalPaid') 
                            <div class="px-4 py-3 bg-danger-50 dark:bg-danger-500/10 border border-danger-200 dark:border-danger-500/20 rounded-xl">
                                <span class="text-xs text-danger-600 dark:text-danger-400 font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    {{ $message }}
                                </span> 
                            </div>
                        @enderror

                        <button wire:click="processPayment"
                            class="w-full py-3.5 px-6 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-bold text-sm rounded-2xl shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 active:scale-95 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group">
                            <span>Konfirmasi Pembayaran</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
