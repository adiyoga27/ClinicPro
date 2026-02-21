<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Dashboard Kasir</x-slot:header>

<div>
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-warning-500/20 transition-all">
            <p class="text-3xl font-bold text-surface-100">{{ $unpaidCount }}</p>
            <p class="text-sm text-surface-500 mt-1">Tagihan Belum Bayar</p>
        </div>
        <div class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-accent-500/20 transition-all">
            <p class="text-3xl font-bold text-surface-100">{{ $paidTodayCount }}</p>
            <p class="text-sm text-surface-500 mt-1">Dibayar Hari Ini</p>
        </div>
        <div class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-primary-500/20 transition-all">
            <p class="text-3xl font-bold text-surface-100">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-sm text-surface-500 mt-1">Pendapatan Hari Ini</p>
        </div>
    </div>

    <!-- Pending Bills -->
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="text-lg font-semibold text-surface-200">Tagihan Belum Dibayar</h3>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($pendingBills as $bill)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-800/50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-surface-200">{{ $bill->patient->name }}</p>
                        <p class="text-xs text-surface-500">{{ $bill->created_at->format('d M Y') }}</p>
                    </div>
                    <p class="text-sm font-semibold text-warning-500">Rp
                        {{ number_format($bill->total_amount, 0, ',', '.') }}
                    </p>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-surface-500 text-sm">
                    Semua tagihan sudah dibayar.
                </div>
            @endforelse
        </div>
    </div>
</div>
