<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Antrian Hari Ini — {{ today()->format('d M Y') }}</x-slot:header>

<div>
    <!-- Add to Queue -->
    <div class="mb-6 p-6 rounded-2xl bg-surface-900/60 border border-white/5">
        <h3 class="text-lg font-semibold text-surface-200 mb-4">Tambah ke Antrian</h3>
        <form wire:submit="addToQueue" class="flex flex-col sm:flex-row gap-3">
            <select wire:model="patient_id" class="flex-1 px-4 py-3 bg-surface-800/50 border border-white/10 rounded-xl text-surface-200 focus:outline-none focus:border-primary-500 transition-all">
                <option value="0">Pilih Pasien</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} {{ $p->nik ? '- '.$p->nik : '' }}</option>
                @endforeach
            </select>
            <select wire:model="doctor_id" class="flex-1 px-4 py-3 bg-surface-800/50 border border-white/10 rounded-xl text-surface-200 focus:outline-none focus:border-primary-500 transition-all">
                <option value="0">Pilih Dokter</option>
                @foreach($doctors as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 transition-all whitespace-nowrap">
                + Tambah
            </button>
        </form>
        @error('patient_id') <p class="mt-2 text-sm text-danger-500">{{ $message }}</p> @enderror
        @error('doctor_id') <p class="mt-2 text-sm text-danger-500">{{ $message }}</p> @enderror
    </div>

    <!-- Queue List -->
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-white/5">
                        <th class="px-6 py-3 font-medium">No.</th>
                        <th class="px-6 py-3 font-medium">Pasien</th>
                        <th class="px-6 py-3 font-medium">Dokter</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($queues as $q)
                        <tr class="hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-full bg-primary-500/10 flex items-center justify-center text-primary-400 font-bold text-sm">{{ $q->queue_no }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-200 font-medium">{{ $q->patient->name }}</td>
                            <td class="px-6 py-4 text-sm text-surface-400">{{ $q->doctor->name }}</td>
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
