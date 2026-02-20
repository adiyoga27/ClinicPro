<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Manajemen Langganan</x-slot:header>

<div>
    {{-- Current Subscription Card --}}
    <div class="rounded-2xl bg-gradient-to-br from-primary-600/20 to-accent-500/10 border border-primary-500/20 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2.5 rounded-xl bg-primary-500/20">
                        <svg class="w-6 h-6 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-surface-100">{{ $clinic?->name ?? 'Klinik' }}</h3>
                        <p class="text-sm text-surface-400">{{ $clinic?->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $subscription?->plan === 'enterprise' ? 'bg-yellow-500/10 text-yellow-400' : ($subscription?->plan === 'professional' ? 'bg-accent-500/10 text-accent-400' : 'bg-surface-800 text-surface-300') }}">
                        {{ ucfirst($subscription?->plan ?? 'basic') }}
                    </span>
                    @if($subscription?->isActive())
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-accent-400">
                            <span class="w-2 h-2 rounded-full bg-accent-400 animate-pulse"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-danger-500">
                            <span class="w-2 h-2 rounded-full bg-danger-500"></span> Expired
                        </span>
                    @endif
                </div>

                {{-- Duration Info --}}
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="p-3 rounded-xl bg-surface-900/50 border border-white/5">
                        <p class="text-2xl font-bold text-surface-100">{{ $daysLeft }}</p>
                        <p class="text-xs text-surface-500 mt-1">Hari Tersisa</p>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-900/50 border border-white/5">
                        <p class="text-sm font-semibold text-surface-200">{{ $subscription?->started_at?->format('d M Y') ?? '-' }}</p>
                        <p class="text-xs text-surface-500 mt-1">Mulai</p>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-900/50 border border-white/5">
                        <p class="text-sm font-semibold text-surface-200">{{ $subscription?->expired_at?->format('d M Y') ?? '-' }}</p>
                        <p class="text-xs text-surface-500 mt-1">Berakhir</p>
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="w-full md:w-48 flex flex-col items-center">
                <div class="relative w-36 h-36">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor" class="text-surface-800" stroke-width="8" />
                        <circle cx="60" cy="60" r="54" fill="none" stroke="url(#progressGrad)" stroke-width="8" stroke-linecap="round"
                            stroke-dasharray="{{ 339.292 }}"
                            stroke-dashoffset="{{ 339.292 * (1 - $percentLeft / 100) }}" />
                        <defs>
                            <linearGradient id="progressGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#3b82f6" />
                                <stop offset="100%" stop-color="#14b8a6" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold text-surface-100">{{ round($percentLeft) }}%</span>
                        <span class="text-xs text-surface-500">tersisa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Renewal Section --}}
    <h3 class="text-xl font-bold text-surface-100 mb-4">Perpanjang Langganan</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @foreach($durationOptions as $key => $option)
            <label class="cursor-pointer">
                <input type="radio" wire:model.live="selectedDuration" value="{{ $key }}" class="hidden peer">
                <div class="p-5 rounded-2xl border-2 transition-all
                    peer-checked:border-primary-500 peer-checked:bg-primary-500/5 peer-checked:shadow-lg peer-checked:shadow-primary-500/10
                    border-white/10 hover:border-white/20 bg-surface-900/60">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-bold text-surface-100">{{ $option['label'] }}</h4>
                        @if($option['discount'] > 0)
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-accent-500/10 text-accent-400">
                                -{{ $option['discount'] }}%
                            </span>
                        @endif
                    </div>
                    @php
                        $prices = \App\Models\Subscription::planPrices();
                        $plan = $subscription?->plan ?? 'basic';
                        $monthly = $prices[$plan] ?? $prices['basic'];
                        $total = $monthly * $option['months'];
                        if ($option['discount'] > 0) $total = (int)($total * (1 - $option['discount'] / 100));
                    @endphp
                    <p class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-surface-500 mt-1">
                        Rp {{ number_format((int)($total / $option['months']), 0, ',', '.') }}/bulan
                    </p>
                </div>
            </label>
        @endforeach
    </div>

    <div class="flex items-center justify-between p-5 rounded-2xl bg-surface-900/60 border border-white/10">
        <div>
            <p class="text-sm text-surface-400">Total Pembayaran</p>
            <p class="text-3xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
                Rp {{ number_format($calculatedPrice, 0, ',', '.') }}
            </p>
        </div>
        <button wire:click="initiatePayment" wire:loading.attr="disabled"
            class="px-8 py-3.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40 transition-all disabled:opacity-50">
            <span wire:loading.remove wire:target="initiatePayment">Bayar Sekarang</span>
            <span wire:loading wire:target="initiatePayment">Memproses...</span>
        </button>
    </div>

    {{-- Midtrans Snap Modal --}}
    @if($showPaymentModal && $snapToken)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-surface-900 border border-white/10 rounded-2xl p-8 w-full max-w-md shadow-2xl text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 rounded-full bg-primary-500/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-100">Pembayaran</h3>
                    <p class="text-sm text-surface-400 mt-1">Klik tombol di bawah untuk membuka halaman pembayaran Midtrans</p>
                </div>

                <button id="pay-button" onclick="payWithSnap()"
                    class="w-full px-6 py-3.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white font-bold rounded-xl shadow-lg transition-all mb-3">
                    Bayar Rp {{ number_format($calculatedPrice, 0, ',', '.') }}
                </button>

                <button wire:click="$set('showPaymentModal', false)"
                    class="w-full px-6 py-3 rounded-xl bg-surface-800 text-surface-300 border border-white/10 hover:bg-surface-700 transition-all">
                    Batal
                </button>
            </div>
        </div>

        <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            function payWithSnap() {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        window.location.reload();
                    },
                    onPending: function(result) {
                        alert('Pembayaran pending. Anda akan menerima notifikasi setelah pembayaran dikonfirmasi.');
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        @this.set('showPaymentModal', false);
                    }
                });
            }
        </script>
    @endif

    {{-- Subscription History --}}
    <div class="mt-8">
        <h3 class="text-lg font-bold text-surface-100 mb-4">Riwayat Langganan</h3>
        <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-white/5">
                            <th class="px-6 py-3 font-medium">Plan</th>
                            <th class="px-6 py-3 font-medium">Harga</th>
                            <th class="px-6 py-3 font-medium">Periode</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse(auth()->user()->clinic->subscriptions()->latest()->take(10)->get() as $sub)
                            <tr class="hover:bg-surface-800/50 transition-colors">
                                <td class="px-6 py-3 text-sm font-medium text-surface-200">{{ ucfirst($sub->plan) }}</td>
                                <td class="px-6 py-3 text-sm text-surface-300">Rp {{ number_format($sub->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-sm text-surface-400">
                                    {{ $sub->started_at?->format('d M Y') }} — {{ $sub->expired_at?->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-accent-500/10 text-accent-400',
                                            'pending' => 'bg-warning-500/10 text-warning-500',
                                            'expired' => 'bg-surface-800 text-surface-400',
                                            'failed' => 'bg-danger-500/10 text-danger-500',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$sub->status] ?? $statusColors['expired'] }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-surface-500">Belum ada riwayat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
