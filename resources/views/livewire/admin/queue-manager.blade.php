<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Antrian Hari Ini — {{ today()->format('d M Y') }}</x-slot:header>

<div>
    <!-- Add to Queue -->
    <div class="mb-6 p-6 rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 flex flex-col md:flex-row gap-4 md:items-end justify-between">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-4">Tambah ke Antrian</h3>
            <form wire:submit="addToQueue" class="flex flex-col sm:flex-row gap-3 w-full">
                <div class="flex-1">
                    <select wire:model="patient_id" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all">
                        <option value="0">Pilih Pasien</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} {{ $p->nik ? '- '.$p->nik : '' }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="mt-1.5 text-xs text-danger-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex-1">
                    <select wire:model="doctor_id" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-white text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all">
                        <option value="0">Pilih Dokter</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <p class="mt-1.5 text-xs text-danger-500">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white text-sm font-bold rounded-xl hover:shadow-lg shadow-md hover:shadow-primary-500/30 shadow-primary-500/20 hover:-translate-y-0.5 transition-all whitespace-nowrap h-fit">
                    + Tambah
                </button>
            </form>
        </div>
    </div>

    <!-- Queue List -->
    <div class="rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-800/20">
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Pasien</th>
                        <th class="px-6 py-4 font-semibold">Dokter</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                    @forelse($queues as $q)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-full bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-sm border border-primary-100 dark:border-transparent">{{ $q->queue_no }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-900 dark:text-surface-200 font-medium">{{ $q->patient->name }}</td>
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">{{ $q->doctor->name }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'waiting' => 'bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-400 border border-surface-200/50 dark:border-transparent',
                                        'in_progress' => 'bg-warning-50 dark:bg-warning-500/10 text-warning-700 dark:text-warning-500 border border-warning-200/50 dark:border-transparent',
                                        'completed' => 'bg-success-50 dark:bg-accent-500/10 text-success-700 dark:text-accent-400 border border-success-200/50 dark:border-transparent',
                                        'skipped' => 'bg-danger-50 dark:bg-danger-500/10 text-danger-700 dark:text-danger-500 border border-danger-200/50 dark:border-transparent',
                                    ];
                                    $statusLabels = [
                                        'waiting' => 'Menunggu',
                                        'in_progress' => 'Diperiksa',
                                        'completed' => 'Selesai',
                                        'skipped' => 'Dilewati',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusColors[$q->status] ?? '' }}">
                                    {{ $statusLabels[$q->status] ?? $q->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-surface-500 text-sm">Belum ada antrian hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
