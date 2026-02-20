<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Antrian Saya — {{ today()->format('d M Y') }}</x-slot:header>

<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-accent-500/10 border border-accent-500/20 text-accent-400 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-5 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-warning-500/20 transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-warning-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-surface-100">{{ $waitingCount }}</p>
                    <p class="text-xs text-surface-500">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="p-5 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-primary-500/20 transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728M9.172 15.828a4 4 0 010-5.656m5.656 0a4 4 0 010 5.656M12 12h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-surface-100">{{ $inProgressCount }}</p>
                    <p class="text-xs text-surface-500">Sedang Diperiksa</p>
                </div>
            </div>
        </div>
        <div class="p-5 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-accent-500/20 transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-surface-100">{{ $completedCount }}</p>
                    <p class="text-xs text-surface-500">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('filterStatus', 'active')"
            class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $filterStatus === 'active' ? 'bg-primary-500/10 text-primary-400 border border-primary-500/30' : 'bg-surface-800/50 text-surface-400 border border-white/5 hover:text-surface-200' }}">
            Aktif
        </button>
        <button wire:click="$set('filterStatus', 'completed')"
            class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $filterStatus === 'completed' ? 'bg-accent-500/10 text-accent-400 border border-accent-500/30' : 'bg-surface-800/50 text-surface-400 border border-white/5 hover:text-surface-200' }}">
            Selesai
        </button>
        <button wire:click="$set('filterStatus', 'all')"
            class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $filterStatus === 'all' ? 'bg-surface-600/30 text-surface-200 border border-white/10' : 'bg-surface-800/50 text-surface-400 border border-white/5 hover:text-surface-200' }}">
            Semua
        </button>
    </div>

    {{-- Queue List --}}
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-white/5">
                        <th class="px-6 py-3 font-medium">No.</th>
                        <th class="px-6 py-3 font-medium">Pasien</th>
                        <th class="px-6 py-3 font-medium">No. RM</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($queues as $q)
                        <tr class="hover:bg-surface-800/50 transition-colors {{ $q->status === 'in_progress' ? 'bg-primary-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="w-9 h-9 rounded-full {{ $q->status === 'in_progress' ? 'bg-primary-500/20 text-primary-400 ring-2 ring-primary-500/30' : 'bg-primary-500/10 text-primary-400' }} flex items-center justify-center font-bold text-sm">
                                    {{ $q->queue_no }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-surface-200">{{ $q->patient->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-400">
                                {{ $q->patient->medical_record_no ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'waiting' => 'bg-surface-800 text-surface-400',
                                        'in_progress' => 'bg-warning-500/10 text-warning-500',
                                        'completed' => 'bg-accent-500/10 text-accent-400',
                                        'skipped' => 'bg-danger-500/10 text-danger-500',
                                    ];
                                    $statusLabels = [
                                        'waiting' => 'Menunggu',
                                        'in_progress' => 'Diperiksa',
                                        'completed' => 'Selesai',
                                        'skipped' => 'Dilewati',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$q->status] ?? '' }}">
                                    {{ $statusLabels[$q->status] ?? $q->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if($q->status === 'waiting')
                                        <button wire:click="callPatient({{ $q->id }})"
                                            class="px-3 py-1.5 rounded-lg bg-primary-600 text-white text-xs font-semibold hover:bg-primary-500 shadow-lg shadow-primary-500/20 transition-all flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                            </svg>
                                            Panggil
                                        </button>
                                        <button wire:click="skipPatient({{ $q->id }})"
                                            class="px-3 py-1.5 rounded-lg bg-surface-800 text-surface-400 text-xs font-medium hover:bg-surface-700 hover:text-surface-200 transition-all">
                                            Lewati
                                        </button>
                                    @elseif($q->status === 'in_progress')
                                        <a href="{{ route('doctor.examination', $q) }}"
                                            class="px-3 py-1.5 rounded-lg bg-accent-600 text-white text-xs font-semibold hover:bg-accent-500 shadow-lg shadow-accent-500/20 transition-all flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Periksa
                                        </a>
                                        <button wire:click="completePatient({{ $q->id }})"
                                            class="px-3 py-1.5 rounded-lg bg-surface-800 text-accent-400 text-xs font-medium hover:bg-accent-500/10 transition-all">
                                            Selesai
                                        </button>
                                    @elseif(in_array($q->status, ['completed', 'skipped']))
                                        <a href="{{ route('doctor.examination', $q) }}"
                                            class="px-3 py-1.5 rounded-lg bg-surface-800 text-surface-300 text-xs font-medium hover:bg-surface-700 hover:text-surface-100 transition-all flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-surface-500 text-sm">
                                @if($filterStatus === 'active')
                                    Tidak ada antrian aktif saat ini.
                                @elseif($filterStatus === 'completed')
                                    Belum ada antrian yang selesai hari ini.
                                @else
                                    Belum ada antrian hari ini.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
