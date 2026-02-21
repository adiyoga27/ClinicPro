<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>
    {{ $showDetail ? 'Detail Rekam Medis' : 'Riwayat Pasien' }}
</x-slot:header>

<div class="space-y-6">
    @if(!$showDetail)
        {{-- Patient List View --}}
        <div class="bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100">Daftar Pasien</h3>
                    <p class="text-sm font-medium text-surface-600 dark:text-surface-500">Cari dan lihat riwayat rekam medis pasien</p>
                </div>
            </div>

            {{-- Search Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama..."
                        class="w-full pl-10 pr-4 py-2 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="nikSearch" placeholder="Cari NIK..."
                        class="w-full pl-10 pr-4 py-2 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                </div>
                <div class="relative">
                    <input type="date" wire:model.live="dobSearch"
                        class="w-full px-4 py-2 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 transition-all">
                </div>
            </div>

            {{-- Patients Table --}}
            <div class="overflow-x-auto rounded-xl border border-surface-200 dark:border-white/5">
                <table class="w-full border-collapse bg-white dark:bg-transparent">
                    <thead>
                        <tr class="text-left text-xs text-surface-600 dark:text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-transparent">
                            <th class="px-4 py-3 font-bold">Pasien</th>
                            <th class="px-4 py-3 font-bold">NIK</th>
                            <th class="px-4 py-3 font-bold">Tgl Lahir</th>
                            <th class="px-4 py-3 font-bold">Kelamin</th>
                            <th class="px-4 py-3 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-white/5 text-sm">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-surface-50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xs ring-1 ring-primary-100 dark:ring-surface-800">
                                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-surface-900 dark:text-surface-200">{{ $patient->name }}</p>
                                            <p class="text-xs font-semibold text-surface-500">RM: {{ $patient->medical_record_no }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-surface-600 dark:text-surface-400 font-mono text-xs font-medium">{{ $patient->nik ?: '-' }}</td>
                                <td class="px-4 py-4 font-medium text-surface-600 dark:text-surface-400">{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}</td>
                                <td class="px-4 py-4 font-medium text-surface-600 dark:text-surface-400">{{ $patient->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td class="px-4 py-4 text-right">
                                    <button wire:click="selectPatient({{ $patient->id }})"
                                        class="px-3 py-1.5 rounded-lg bg-white dark:bg-surface-800 text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-500 hover:text-primary-700 dark:hover:text-white border border-surface-200 dark:border-primary-500/20 transition-all text-xs font-bold shadow-sm dark:shadow-none">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-surface-500 dark:text-surface-600 font-medium">Tidak ada data pasien ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $patients->links() }}
            </div>
        </div>
    @else
        {{-- Patient Detail View --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Patient Profile Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 rounded-2xl p-6 relative overflow-hidden group shadow-sm dark:shadow-none">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-50 dark:bg-primary-500/10 rounded-bl-full -mr-16 -mt-16 group-hover:bg-primary-100 dark:group-hover:bg-primary-500/20 transition-all"></div>
                    
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="w-24 h-24 rounded-full overflow-hidden mb-4 ring-4 ring-white dark:ring-surface-800 border-2 border-primary-100 dark:border-primary-500/20 shadow-xl">
                            @if($selectedPatient->photo_path)
                                <img src="{{ asset('storage/' . $selectedPatient->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-surface-50 dark:bg-surface-800 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-2xl">
                                    {{ strtoupper(substr($selectedPatient->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-surface-900 dark:text-white">{{ $selectedPatient->name }}</h2>
                        <p class="text-primary-600 dark:text-primary-400 text-sm font-bold mt-1">No. RM: {{ $selectedPatient->medical_record_no }}</p>
                    </div>

                    <div class="mt-8 space-y-4 relative z-10">
                        <div class="flex items-center justify-between py-2 border-b border-surface-200 dark:border-white/5">
                            <span class="text-surface-500 text-sm font-medium">NIK</span>
                            <span class="text-surface-900 dark:text-surface-200 text-sm font-mono font-medium">{{ $selectedPatient->nik ?: '-' }}</span>
                        </div>
                        @if($selectedPatient->mother_name)
                        <div class="flex items-center justify-between py-2 border-b border-surface-200 dark:border-white/5">
                            <span class="text-surface-500 text-sm font-medium">Ibu Kandung</span>
                            <span class="text-surface-900 dark:text-surface-200 text-sm font-medium">{{ $selectedPatient->mother_name }}</span>
                        </div>
                        @endif
                        @if($selectedPatient->mother_nik)
                        <div class="flex items-center justify-between py-2 border-b border-surface-200 dark:border-white/5">
                            <span class="text-surface-500 text-sm font-medium">NIK Ibu</span>
                            <span class="text-surface-900 dark:text-surface-200 text-sm font-mono font-medium">{{ $selectedPatient->mother_nik }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between py-2 border-b border-surface-200 dark:border-white/5">
                            <span class="text-surface-500 text-sm font-medium">Tgl Lahir</span>
                            <span class="text-surface-900 dark:text-surface-200 text-sm font-medium">{{ $selectedPatient->birth_date ? $selectedPatient->birth_date->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-surface-200 dark:border-white/5">
                            <span class="text-surface-500 text-sm font-medium">Jenis Kelamin</span>
                            <span class="text-surface-900 dark:text-surface-200 text-sm font-medium">{{ $selectedPatient->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="flex flex-col py-2">
                            <span class="text-surface-500 text-sm mb-1 font-medium">Alamat</span>
                            <span class="text-surface-900 dark:text-surface-200 text-sm font-medium">{{ $selectedPatient->address ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Medical Record Timeline --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
                    <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100">Riwayat Pemeriksaan</h3>
                    <div class="relative w-full md:w-64">
                         <input type="date" wire:model.live="recordSearchDate"
                            class="w-full px-4 py-2 bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 transition-all shadow-sm dark:shadow-none font-medium"
                            placeholder="Cari Tanggal...">
                    </div>
                </div>
                
                @forelse($medicalRecords as $record)
                    <div class="bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 rounded-2xl overflow-hidden shadow-sm dark:shadow-none">
                        <div class="bg-surface-50 dark:bg-white/5 px-6 py-4 flex items-center justify-between border-b border-surface-200 dark:border-white/5">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $record->visit_date->format('d M Y') }}</span>
                                <span class="w-1 h-1 rounded-full bg-surface-300 dark:bg-surface-700"></span>
                                <span class="text-xs font-semibold text-surface-500">Oleh: {{ $record->doctor->name }}</span>
                            </div>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-5 border-b border-surface-200 dark:border-white/5">
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-surface-500 dark:text-surface-600 mb-1">TB / BB</p>
                                    <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $record->height ?: '-' }}cm / {{ $record->weight ?: '-' }}kg</p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-surface-500 dark:text-surface-600 mb-1">Tensi</p>
                                    <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $record->blood_pressure_systolic ?: '-' }}/{{ $record->blood_pressure_diastolic ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-surface-500 dark:text-surface-600 mb-1">Suhu</p>
                                    <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $record->temperature ?: '-' }}°C</p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-surface-500 dark:text-surface-600 mb-1">SpO2</p>
                                    <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $record->spo2 ?: '-' }}%</p>
                                </div>
                            </div>

                            <div class="space-y-4 pt-2">
                                <div>
                                    <p class="text-xs font-bold text-surface-500 dark:text-surface-400 mb-1">Subjective (Keluhan)</p>
                                    <p class="text-sm font-medium text-surface-900 dark:text-surface-300 bg-surface-50 dark:bg-surface-800/50 p-3 rounded-xl border border-surface-200 dark:border-white/5 italic">
                                        {{ $record->subjective }}
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-bold text-surface-500 dark:text-surface-400 mb-2">Diagnosis (ICD-10)</p>
                                        <div class="space-y-1.5">
                                            @foreach($record->diagnoses as $diag)
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="px-1.5 py-0.5 rounded bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 border border-primary-200 dark:border-transparent font-mono font-bold">{{ $diag->icd10Code->code }}</span>
                                                    <span class="font-medium text-surface-900 dark:text-surface-300">{{ $diag->icd10Code->name_id ?: $diag->icd10Code->name_en }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-surface-500 dark:text-surface-400 mb-2">Rencana (Plan)</p>
                                        <p class="text-sm font-medium text-surface-900 dark:text-surface-300">{{ $record->plan ?: '-' }}</p>
                                    </div>
                                </div>

                                @if($record->billing && $record->billing->items->where('name', 'like', 'Tindakan:%')->count() > 0)
                                    <div>
                                        <p class="text-xs font-bold text-surface-500 dark:text-surface-400 mb-2">Jasa & Tindakan Medis</p>
                                        <div class="bg-surface-50/50 dark:bg-surface-800/30 rounded-xl border border-surface-200 dark:border-white/5 overflow-hidden p-4 mb-4 space-y-2">
                                            @foreach($record->billing->items->where('name', 'like', 'Tindakan:%') as $item)
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="font-medium text-surface-800 dark:text-surface-200">{{ str_replace('Tindakan: ', '', $item->name) }}</span>
                                                    <span class="text-surface-600 dark:text-surface-400 font-mono font-medium text-xs">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($record->prescription && $record->prescription->items->count() > 0)
                                    <div>
                                        <p class="text-xs font-bold text-surface-500 dark:text-surface-400 mb-2">Resep Obat</p>
                                        <div class="bg-surface-50/50 dark:bg-surface-800/30 rounded-xl border border-surface-200 dark:border-white/5 overflow-hidden">
                                            <table class="w-full text-xs">
                                                <thead class="bg-surface-100 dark:bg-white/5 text-surface-600 dark:text-surface-500 uppercase font-black tracking-wider">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left">Obat</th>
                                                        <th class="px-4 py-3 text-left">Dosis</th>
                                                        <th class="px-4 py-3 text-left">Aturan</th>
                                                        <th class="px-4 py-3 text-right">Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-surface-200 dark:divide-white/5">
                                                    @foreach($record->prescription->items as $item)
                                                        <tr>
                                                            <td class="px-4 py-3 text-surface-900 dark:text-surface-200 font-bold">{{ $item->drug_name }}</td>
                                                            <td class="px-4 py-3 font-medium text-surface-600 dark:text-surface-400">{{ $item->dosage ?: '-' }}</td>
                                                            <td class="px-4 py-3 font-medium text-surface-600 dark:text-surface-400">{{ $item->frequency }} ({{ $item->duration }})</td>
                                                            <td class="px-4 py-3 text-right font-bold text-surface-900 dark:text-surface-200">{{ $item->qty }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none rounded-2xl p-12 text-center text-surface-500 dark:text-surface-600 font-medium">
                        Belum ada riwayat pemeriksaan untuk pasien ini.
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $medicalRecords->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
