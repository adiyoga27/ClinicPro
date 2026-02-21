<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Kasir & Pembayaran</x-slot:header>

<div class="space-y-6 lg:space-y-8 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Kasir & Pembayaran</h1>
            <p class="text-sm text-surface-400 mt-2">Kelola transaksi dan pembayaran pasien dengan mudah.</p>
        </div>
        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap p-1 bg-surface-900/40 rounded-xl backdrop-blur-xl border border-white/5 w-full sm:w-auto overflow-x-auto scrollbar-hide">
                @foreach(['all' => 'Semua', 'unpaid' => 'Belum Bayar', 'paid' => 'Lunas'] as $key => $label)
                    <button wire:click="$set('statusFilter', '{{ $key }}')"
                        class="flex-1 min-w-[100px] sm:flex-none px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ $statusFilter === $key ? 'bg-primary-500/20 text-primary-400 shadow-sm ring-1 ring-white/10' : 'text-surface-400 hover:text-surface-200 hover:bg-surface-800/50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-surface-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / no. RM..."
                    class="block w-full pl-10 pr-3 py-2 border border-white/10 rounded-xl leading-5 bg-surface-900/40 text-white placeholder-surface-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm transition-all duration-300 backdrop-blur-xl">
            </div>
        </div>
    </div>

    <!-- Billings Table Card -->
    <div class="bg-surface-900/40 rounded-3xl shadow-sm border border-white/5 overflow-hidden backdrop-blur-2xl">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-surface-950/50 border-b border-white/5">
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider">ID Tagihan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider text-right">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-surface-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($billings as $billing)
                        <tr class="hover:bg-surface-800/50 transition-colors group relative">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-primary-500/10 flex items-center justify-center text-primary-400 font-bold text-sm ring-2 ring-surface-800 shadow-sm">
                                        {{ strtoupper(substr($billing->patient->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-white group-hover:text-primary-400 transition-colors">
                                            {{ $billing->patient->name }}
                                        </p>
                                        <p class="text-xs text-surface-400 font-medium">RM: {{ $billing->patient->medical_record_no }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-surface-900/60 text-surface-300 ring-1 ring-inset ring-white/10">
                                    #{{ $billing->id }}
                                </span>
                                <div class="text-[10px] text-surface-500 mt-1">{{ $billing->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                @if($billing->status === 'paid')
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 ring-1 ring-inset ring-emerald-500/20 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Lunas
                                        </span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($billing->payments as $payment)
                                                <span class="text-[9px] font-medium text-surface-500 uppercase tracking-wider">{{ $payment->payment_method }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 ring-1 ring-inset ring-amber-500/20 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-sm font-bold text-white">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                @if($billing->status === 'unpaid')
                                    <button wire:click="selectBilling({{ $billing->id }})"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white transition-all bg-primary-600 rounded-xl hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600 hover:shadow-lg hover:shadow-primary-500/30 active:scale-95">
                                        Bayar
                                        <svg class="w-4 h-4 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                @else
                                    <button disabled
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-surface-500 bg-surface-800/50 rounded-xl cursor-not-allowed border border-white/5">
                                        Selesai
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
            <div class="px-6 py-4 border-t border-white/5">
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
            <div class="relative bg-surface-900/80 backdrop-blur-2xl border border-white/10 rounded-3xl w-full max-w-4xl max-h-[95vh] sm:max-h-[90vh] overflow-hidden flex flex-col shadow-2xl ring-1 ring-black/5">
                
                <!-- Header -->
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-white/10 flex items-start sm:items-center justify-between bg-surface-900/50 gap-4">
                    <div class="space-y-1">
                        <h3 class="text-lg sm:text-xl font-bold text-white tracking-tight">Proses Pembayaran</h3>
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-surface-400">
                            <span class="font-medium text-surface-300">{{ $selectedBilling->patient->name }}</span>
                            <span class="w-1 h-1 bg-surface-600 rounded-full hidden sm:block"></span>
                            <span>RM: {{ $selectedBilling->patient->medical_record_no }}</span>
                        </div>
                    </div>
                    <button wire:click="$set('showModal', false)" class="p-2 bg-surface-800 hover:bg-surface-700 text-surface-400 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500/50">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 flex flex-col lg:grid lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-12">
                    <!-- Billing Details -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Invoice Items -->
                        <div class="bg-surface-950/50 rounded-2xl p-4 sm:p-6 border border-white/5">
                            <h4 class="text-xs font-semibold text-surface-500 uppercase tracking-widest mb-4">Rincian Tagihan</h4>
                            <div class="space-y-3">
                                @foreach($selectedBilling->items as $item)
                                    <div class="flex flex-col sm:flex-row justify-between text-sm group gap-1 sm:gap-0">
                                        <span class="text-surface-300">{{ $item->name }} <span class="text-surface-500 text-xs ml-1 sm:ml-2">x{{ $item->qty }}</span></span>
                                        <span class="font-medium text-white whitespace-nowrap">Rp {{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                <div class="pt-4 mt-4 border-t border-white/10 flex justify-between items-center">
                                    <span class="font-semibold text-white">Total Tagihan</span>
                                    <span class="text-xl font-bold text-primary-400">Rp {{ number_format($selectedBilling->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Balances -->
                        <div class="bg-primary-500/5 rounded-2xl p-5 border border-primary-500/20 space-y-3 relative overflow-hidden">
                            <!-- Decorative element -->
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary-500/10 rounded-full blur-2xl pointer-events-none"></div>
                            
                            <div class="flex items-center justify-between relative z-10">
                                <span class="text-xs font-medium text-primary-400/80 uppercase tracking-wider">Saldo Deposit Pasien</span>
                                <span class="font-bold text-primary-300">Rp {{ number_format($selectedBilling->patient->deposit_balance, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="pt-3 border-t border-primary-500/20 flex items-center justify-between relative z-10">
                                <span class="text-xs font-medium text-primary-400/80 uppercase tracking-wider">Sisa Pembayaran</span>
                                <span class="text-lg font-bold {{ abs($this->totalPaid - $selectedBilling->total_amount) < 0.01 ? 'text-emerald-400' : 'text-amber-400' }}">
                                    Rp {{ number_format($selectedBilling->total_amount - $this->totalPaid, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-semibold text-surface-500 uppercase tracking-widest">Metode Pembayaran</h4>
                            <button type="button" wire:click="addPaymentRow" class="text-xs font-medium text-primary-400 hover:text-primary-300 flex items-center gap-1 transition-colors px-3 py-1.5 rounded-full hover:bg-primary-500/10">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Split Bill
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($paymentRows as $index => $row)
                                <div class="p-4 sm:p-5 rounded-2xl bg-surface-800/40 border border-white/5 relative group transition-all duration-300 hover:border-primary-500/30">
                                    @if(count($paymentRows) > 1)
                                        <button wire:click="removePaymentRow({{ $index }})" class="absolute -top-3 -right-3 z-10 p-1.5 text-surface-500 hover:text-red-400 bg-surface-900 rounded-full shadow-sm ring-1 ring-white/10 transition-all sm:opacity-0 sm:group-hover:opacity-100 scale-100 sm:scale-90 sm:group-hover:scale-100">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-surface-500 mb-1">Metode</label>
                                            <select wire:model.live="paymentRows.{{ $index }}.method" 
                                                class="w-full pl-3 pr-8 py-2.5 bg-surface-900 border border-surface-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                                                <option value="cash">💵 Cash / Tunai</option>
                                                <option value="debit">💳 Debit / Kartu</option>
                                                <option value="qris">📱 QRIS</option>
                                                <option value="bpjs">🏥 BPJS Kesehatan</option>
                                                <option value="deposit">💰 Potong Deposit</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-500 mb-1">Nominal (Rp)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-surface-500 text-sm font-medium">Rp</span>
                                                </div>
                                                <input wire:model.live="paymentRows.{{ $index }}.amount" type="number"
                                                    class="w-full pl-9 pr-3 py-2.5 bg-surface-900 border border-surface-700 rounded-xl text-sm text-white font-semibold focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('totalPaid') 
                            <div class="px-4 py-3 bg-red-500/10 border border-red-500/20 rounded-xl">
                                <span class="text-xs text-red-400 font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    {{ $message }}
                                </span> 
                            </div>
                        @enderror

                        <button wire:click="processPayment"
                            class="w-full py-4 px-6 bg-primary-600 hover:bg-primary-500 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-primary-500/20 active:scale-95 transition-all flex items-center justify-center gap-2 group">
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
