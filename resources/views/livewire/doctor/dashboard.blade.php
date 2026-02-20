<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Dashboard Dokter</x-slot:header>

<div>
    {{-- Flash Messages --}}
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

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-primary-500/20 transition-all">
            <p class="text-3xl font-bold text-surface-100">{{ $myQueue->count() }}</p>
            <p class="text-sm text-surface-500 mt-1">Antrian Saya</p>
        </div>
        <div class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-accent-500/20 transition-all">
            <p class="text-3xl font-bold text-surface-100">{{ $completedToday }}</p>
            <p class="text-sm text-surface-500 mt-1">Selesai Hari Ini</p>
        </div>
        <div class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-warning-500/20 transition-all">
            <p class="text-3xl font-bold text-surface-100">{{ $totalRecords }}</p>
            <p class="text-sm text-surface-500 mt-1">Total Rekam Medis</p>
        </div>
    </div>

    <!-- Today's Queue -->
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="text-lg font-semibold text-surface-200">Antrian Pasien Hari Ini</h3>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($myQueue as $queue)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-800/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-primary-500/10 flex items-center justify-center text-primary-400 font-bold text-sm">
                            {{ $queue->queue_no }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-surface-200">{{ $queue->patient->name }}</p>
                            <p class="text-xs text-surface-500">No. RM: {{ $queue->patient->medical_record_no ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium {{ $queue->status === 'in_progress' ? 'bg-warning-500/10 text-warning-500' : 'bg-surface-800 text-surface-400' }}">
                            {{ $queue->status === 'in_progress' ? 'Sedang Diperiksa' : 'Menunggu' }}
                        </span>
                        <a href="{{ route('doctor.examination', $queue) }}"
                            class="px-4 py-2 rounded-xl bg-primary-600 text-white text-xs font-semibold hover:bg-primary-500 shadow-lg shadow-primary-500/20 transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Periksa
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-surface-500 text-sm">
                    Tidak ada antrian hari ini.
                </div>
            @endforelse
        </div>
    </div>
</div>