<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Antrian Hari Ini — {{ today()->format('d M Y') }}</x-slot:header>

<div>
    <div class="flex justify-end mb-6">
        <button wire:click="create"
            class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Antrian
        </button>
    </div>

    <!-- Modal Form -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            wire:click.self="$set('showForm', false)">
            <div class="bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 w-full max-w-md shadow-xl dark:shadow-2xl">
                <h3 class="text-xl font-bold text-surface-900 dark:text-white mb-6">Tambah ke Antrian</h3>
                
                <form wire:submit="addToQueue" class="space-y-4 overflow-visible">
                    @php
                        $patientOptions = $patients->map(fn($p) => ['value' => $p->id, 'label' => $p->name . ($p->nik ? " - {$p->nik}" : '') . ($p->satu_sehat_id ? ' (✓ Satu Sehat)' : '')])->toArray();
                        $doctorOptions = $doctors->map(fn($d) => ['value' => $d->id, 'label' => $d->name])->toArray();
                        $roomOptions = $rooms->map(fn($r) => ['value' => $r->id, 'label' => $r->name])->toArray();
                    @endphp

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-100 mb-1.5">Pilih Pasien *</label>
                        <x-searchable-select wireModel="patient_id" :options="$patientOptions" placeholder="Pilih Pasien..." />
                        @error('patient_id') <p class="mt-1.5 text-xs text-danger-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="z-40 relative">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-100 mb-1.5">Pilih Dokter *</label>
                        <x-searchable-select wireModel="doctor_id" :options="$doctorOptions" placeholder="Pilih Dokter..." />
                        @error('doctor_id') <p class="mt-1.5 text-xs text-danger-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="z-30 relative">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-100 mb-1.5">Pilih Poli/Ruangan *</label>
                        <x-searchable-select wireModel="room_id" :options="$roomOptions" placeholder="Pilih Poli/Ruang..." />
                        @error('room_id') <p class="mt-1.5 text-xs text-danger-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-5 py-2.5 rounded-xl bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-100 border border-surface-200 dark:border-white/10 shadow-sm hover:bg-surface-50 dark:hover:bg-surface-700 transition-all text-sm font-bold">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-bold text-sm shadow-md shadow-primary-500/20 hover:shadow-lg hover:shadow-primary-500/30 hover:-translate-y-0.5 transition-all">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Queue List -->
    <div class="rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-800/20">
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Pasien</th>
                        <th class="px-6 py-4 font-semibold">Dokter</th>
                        <th class="px-6 py-4 font-semibold">Poli/Ruangan</th>
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
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">{{ $q->room->name ?? '-' }}</td>
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
