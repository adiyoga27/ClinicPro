<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>
    Pemeriksaan — {{ $patient->name }}
</x-slot:header>

<div>
    {{-- Stepper Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between max-w-3xl mx-auto">
            @php
                $steps = ['Vital Signs', 'Keluhan', 'Diagnosis', 'Resep Obat', 'Ringkasan'];
            @endphp
            @foreach($steps as $i => $label)
                @php $s = $i + 1; @endphp
                <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                    <button wire:click="goToStep({{ $s }})" class="flex flex-col items-center gap-1.5 group cursor-pointer">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all
                                {{ $step >= $s ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/25' : 'bg-surface-800 text-surface-500 border border-white/10' }}">
                            @if($step > $s)
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                {{ $s }}
                            @endif
                        </div>
                        <span
                            class="text-[11px] font-medium {{ $step >= $s ? 'text-primary-400' : 'text-surface-600' }}">{{ $label }}</span>
                    </button>
                    @if($i < count($steps) - 1)
                        <div
                            class="flex-1 h-0.5 mx-2 mt-[-20px] {{ $step > $s ? 'bg-primary-500' : 'bg-surface-800' }} transition-all">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Patient Info Bar --}}
    <div class="mb-6 p-4 rounded-2xl bg-surface-900/60 border border-white/5 flex items-center gap-4">
        <div
            class="w-12 h-12 rounded-full bg-primary-500/10 flex items-center justify-center text-primary-400 font-bold text-lg">
            {{ strtoupper(substr($patient->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-surface-100">{{ $patient->name }}</p>
            <p class="text-xs text-surface-500">No. RM: {{ $patient->medical_record_no ?? '-' }} · Antrian
                #{{ $queue->queue_no }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-surface-500">Tanggal</p>
            <p class="text-sm font-medium text-surface-300">{{ today()->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Step Content --}}
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 p-6">

        {{-- ═══ Step 1: Vital Signs ═══ --}}
        @if($step === 1)
            <div>
                <h3 class="text-lg font-bold text-surface-100 mb-1">Screening Umum</h3>
                <p class="text-sm text-surface-500 mb-6">Masukkan data vital signs pasien</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" wire:model="height" placeholder="165.0"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Berat Badan (kg)</label>
                        <input type="number" step="0.1" wire:model="weight" placeholder="65.0"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Tekanan Darah (mmHg)</label>
                        <div class="flex items-center gap-2">
                            <input type="number" wire:model="blood_pressure_systolic" placeholder="120"
                                class="flex-1 px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                            <span class="text-surface-500 font-bold">/</span>
                            <input type="number" wire:model="blood_pressure_diastolic" placeholder="80"
                                class="flex-1 px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Suhu (°C)</label>
                        <input type="number" step="0.1" wire:model="temperature" placeholder="36.5"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Denyut Nadi (bpm)</label>
                        <input type="number" wire:model="heart_rate" placeholder="80"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Laju Napas (/min)</label>
                        <input type="number" wire:model="respiratory_rate" placeholder="18"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">SpO2 (%)</label>
                        <input type="number" wire:model="spo2" placeholder="98"
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Catatan Alergi</label>
                        <input type="text" wire:model="allergy_notes" placeholder="Contoh: alergi penisilin, seafood..."
                            class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══ Step 2: Subjective (Keluhan) ═══ --}}
        @if($step === 2)
            <div>
                <h3 class="text-lg font-bold text-surface-100 mb-1">Keluhan Pasien (Subjective)</h3>
                <p class="text-sm text-surface-500 mb-6">Catat keluhan utama pasien</p>

                <div>
                    <label class="block text-xs font-medium text-surface-400 mb-1.5">Keluhan Utama <span
                            class="text-danger-500">*</span></label>
                    <textarea wire:model="subjective" rows="5"
                        placeholder="Contoh: pasien mengeluh demam selama 3 hari, disertai batuk dan pilek..."
                        class="w-full px-4 py-3 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none"></textarea>
                    @error('subjective') <p class="text-xs text-danger-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endif

        {{-- ═══ Step 3: Objective & Diagnosis ═══ --}}
        @if($step === 3)
            <div>
                <h3 class="text-lg font-bold text-surface-100 mb-1">Pemeriksaan & Diagnosis</h3>
                <p class="text-sm text-surface-500 mb-6">Hasil pemeriksaan fisik dan diagnosis ICD-10</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Pemeriksaan Fisik
                            (Objective)</label>
                        <textarea wire:model="objective" rows="3" placeholder="Hasil pemeriksaan fisik..."
                            class="w-full px-4 py-3 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Assessment</label>
                        <textarea wire:model="assessment" rows="2" placeholder="Penilaian / kesimpulan klinis..."
                            class="w-full px-4 py-3 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none"></textarea>
                    </div>

                    {{-- ICD-10 Search --}}
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Diagnosis ICD-10</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="icdSearch"
                                placeholder="Cari kode ICD-10 (misal: J06, demam, flu)..."
                                class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">

                            @if(count($this->icdResults) > 0)
                                <div
                                    class="absolute z-30 w-full mt-1 bg-surface-800 border border-white/10 rounded-xl shadow-2xl max-h-60 overflow-y-auto">
                                    @foreach($this->icdResults as $icd)
                                        <button wire:click="addDiagnosis({{ $icd['id'] }})" type="button"
                                            class="w-full px-4 py-2.5 text-left hover:bg-surface-700 transition-colors flex items-center gap-3 border-b border-white/5 last:border-0">
                                            <span
                                                class="px-2 py-0.5 rounded bg-primary-500/10 text-primary-400 text-xs font-mono font-bold">{{ $icd['code'] }}</span>
                                            <span class="text-sm text-surface-300">{{ $icd['name_id'] ?: $icd['name_en'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Selected Diagnoses --}}
                        @if(!empty($selectedDiagnoses))
                            <div class="mt-3 space-y-2">
                                @foreach($selectedDiagnoses as $index => $diag)
                                    <div
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-surface-800/50 border border-white/5">
                                        <span
                                            class="px-2 py-0.5 rounded text-xs font-bold {{ $diag['type'] === 'primary' ? 'bg-accent-500/10 text-accent-400' : 'bg-surface-700 text-surface-400' }}">
                                            {{ $diag['type'] === 'primary' ? 'Utama' : 'Sekunder' }}
                                        </span>
                                        <span
                                            class="px-2 py-0.5 rounded bg-primary-500/10 text-primary-400 text-xs font-mono font-bold">{{ $diag['code'] }}</span>
                                        <span class="flex-1 text-sm text-surface-300">{{ $diag['name'] }}</span>
                                        <button wire:click="removeDiagnosis({{ $index }})"
                                            class="text-surface-600 hover:text-danger-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══ Step 4: Plan & Prescriptions ═══ --}}
        @if($step === 4)
            <div>
                <h3 class="text-lg font-bold text-surface-100 mb-1">Penanganan & Resep Obat</h3>
                <p class="text-sm text-surface-500 mb-6">Rencana penanganan dan resep obat</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Rencana Penanganan (Plan)</label>
                        <textarea wire:model="plan" rows="3" placeholder="Rencana terapi, tindakan, edukasi pasien..."
                            class="w-full px-4 py-3 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none"></textarea>
                    </div>

                    {{-- Medicine Search --}}
                    <div>
                        <label class="block text-xs font-medium text-surface-400 mb-1.5">Tambah Obat</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="medicineSearch"
                                placeholder="Cari obat (misal: paracetamol, amoxicillin)..."
                                class="w-full px-4 py-2.5 bg-surface-800 border border-white/10 rounded-xl text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all">

                            @if(count($this->medicineResults) > 0)
                                <div
                                    class="absolute z-30 w-full mt-1 bg-surface-800 border border-white/10 rounded-xl shadow-2xl max-h-60 overflow-y-auto">
                                    @foreach($this->medicineResults as $med)
                                        <button wire:click="addMedicine({{ $med['id'] }})" type="button"
                                            class="w-full px-4 py-2.5 text-left hover:bg-surface-700 transition-colors flex items-center justify-between border-b border-white/5 last:border-0">
                                            <div>
                                                <p class="text-sm font-medium text-surface-200">{{ $med['name'] }}</p>
                                                <p class="text-xs text-surface-500">{{ $med['category'] }} · {{ $med['unit'] }}</p>
                                            </div>
                                            <span class="text-sm font-semibold text-accent-400">Rp
                                                {{ number_format($med['price'], 0, ',', '.') }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Prescription Items Table --}}
                    @if(!empty($prescriptionItems))
                        <div class="rounded-xl border border-white/5 overflow-hidden">
                            <table class="w-full">
                                <thead>
                                    <tr
                                        class="text-left text-xs text-surface-500 uppercase tracking-wider border-b border-white/5 bg-surface-800/50">
                                        <th class="px-4 py-2.5 font-medium">Obat</th>
                                        <th class="px-4 py-2.5 font-medium">Dosis</th>
                                        <th class="px-4 py-2.5 font-medium">Frekuensi</th>
                                        <th class="px-4 py-2.5 font-medium">Durasi</th>
                                        <th class="px-4 py-2.5 font-medium w-20">Qty</th>
                                        <th class="px-4 py-2.5 font-medium text-right">Subtotal</th>
                                        <th class="px-4 py-2.5 font-medium w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($prescriptionItems as $index => $item)
                                        <tr class="hover:bg-surface-800/30 transition-colors">
                                            <td class="px-4 py-2.5">
                                                <p class="text-sm font-medium text-surface-200">{{ $item['drug_name'] }}</p>
                                                <p class="text-xs text-surface-500">Rp
                                                    {{ number_format($item['price'], 0, ',', '.') }}/{{ $item['unit'] }}</p>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="text" wire:model="prescriptionItems.{{ $index }}.dosage"
                                                    placeholder="500mg"
                                                    class="w-full px-2 py-1.5 bg-surface-800 border border-white/10 rounded-lg text-surface-200 text-xs focus:border-primary-500 transition-all">
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="text" wire:model="prescriptionItems.{{ $index }}.frequency"
                                                    placeholder="3x sehari"
                                                    class="w-full px-2 py-1.5 bg-surface-800 border border-white/10 rounded-lg text-surface-200 text-xs focus:border-primary-500 transition-all">
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="text" wire:model="prescriptionItems.{{ $index }}.duration"
                                                    placeholder="3 hari"
                                                    class="w-full px-2 py-1.5 bg-surface-800 border border-white/10 rounded-lg text-surface-200 text-xs focus:border-primary-500 transition-all">
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="number" min="1" wire:model.live="prescriptionItems.{{ $index }}.qty"
                                                    class="w-full px-2 py-1.5 bg-surface-800 border border-white/10 rounded-lg text-surface-200 text-xs text-center focus:border-primary-500 transition-all">
                                            </td>
                                            <td class="px-4 py-2.5 text-right">
                                                <span class="text-sm font-semibold text-surface-200">Rp
                                                    {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <button wire:click="removeMedicine({{ $index }})"
                                                    class="text-surface-600 hover:text-danger-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Billing Preview --}}
                    <div
                        class="p-4 rounded-xl bg-gradient-to-r from-primary-500/5 to-accent-500/5 border border-primary-500/10">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-surface-400">Jasa Dokter</span>
                                <span class="text-surface-200">Rp {{ number_format($doctorFee, 0, ',', '.') }}</span>
                            </div>
                            @foreach($prescriptionItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-surface-400">{{ $item['drug_name'] }} × {{ $item['qty'] }}</span>
                                    <span class="text-surface-200">Rp
                                        {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <div class="border-t border-white/10 pt-2 mt-2 flex justify-between">
                                <span class="text-sm font-bold text-surface-200">Total Estimasi</span>
                                <span
                                    class="text-lg font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
                                    Rp {{ number_format($this->totalBilling, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══ Step 5: Summary ═══ --}}
        @if($step === 5)
            <div>
                <h3 class="text-lg font-bold text-surface-100 mb-1">Ringkasan Pemeriksaan</h3>
                <p class="text-sm text-surface-500 mb-6">Periksa kembali data sebelum menyimpan</p>

                <div class="space-y-4">
                    {{-- Vital Signs Summary --}}
                    <div class="p-4 rounded-xl bg-surface-800/50 border border-white/5">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-surface-500 mb-3">Vital Signs</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                            @if($height)
                                <div><span class="text-surface-500">TB:</span> <span class="text-surface-200">{{ $height }}
                            cm</span></div> @endif
                            @if($weight)
                                <div><span class="text-surface-500">BB:</span> <span class="text-surface-200">{{ $weight }}
                            kg</span></div> @endif
                            @if($blood_pressure_systolic)
                                <div><span class="text-surface-500">TD:</span> <span
                                        class="text-surface-200">{{ $blood_pressure_systolic }}/{{ $blood_pressure_diastolic }}
                            mmHg</span></div> @endif
                            @if($temperature)
                                <div><span class="text-surface-500">Suhu:</span> <span
                            class="text-surface-200">{{ $temperature }} °C</span></div> @endif
                            @if($heart_rate)
                                <div><span class="text-surface-500">Nadi:</span> <span
                            class="text-surface-200">{{ $heart_rate }} bpm</span></div> @endif
                            @if($respiratory_rate)
                                <div><span class="text-surface-500">RR:</span> <span
                            class="text-surface-200">{{ $respiratory_rate }} /min</span></div> @endif
                            @if($spo2)
                                <div><span class="text-surface-500">SpO2:</span> <span
                            class="text-surface-200">{{ $spo2 }}%</span></div> @endif
                            @if($allergy_notes)
                                <div class="col-span-2"><span class="text-surface-500">Alergi:</span> <span
                            class="text-warning-400">{{ $allergy_notes }}</span></div> @endif
                        </div>
                    </div>

                    {{-- SOAP Summary --}}
                    <div class="p-4 rounded-xl bg-surface-800/50 border border-white/5">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-surface-500 mb-3">SOAP</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="text-surface-500 font-medium">S:</span> <span
                                    class="text-surface-200">{{ $subjective ?: '-' }}</span></div>
                            <div><span class="text-surface-500 font-medium">O:</span> <span
                                    class="text-surface-200">{{ $objective ?: '-' }}</span></div>
                            <div><span class="text-surface-500 font-medium">A:</span> <span
                                    class="text-surface-200">{{ $assessment ?: '-' }}</span></div>
                            <div><span class="text-surface-500 font-medium">P:</span> <span
                                    class="text-surface-200">{{ $plan ?: '-' }}</span></div>
                        </div>
                    </div>

                    {{-- Diagnoses Summary --}}
                    @if(!empty($selectedDiagnoses))
                        <div class="p-4 rounded-xl bg-surface-800/50 border border-white/5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-surface-500 mb-3">Diagnosis</h4>
                            <div class="space-y-1.5">
                                @foreach($selectedDiagnoses as $diag)
                                    <div class="flex items-center gap-2 text-sm">
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $diag['type'] === 'primary' ? 'bg-accent-500/10 text-accent-400' : 'bg-surface-700 text-surface-400' }}">{{ $diag['type'] === 'primary' ? 'P' : 'S' }}</span>
                                        <span class="font-mono text-primary-400">{{ $diag['code'] }}</span>
                                        <span class="text-surface-300">{{ $diag['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Billing Summary --}}
                    <div
                        class="p-4 rounded-xl bg-gradient-to-r from-primary-500/5 to-accent-500/5 border border-primary-500/10">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-surface-500 mb-3">Rincian Biaya</h4>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-surface-400">Jasa Dokter</span>
                                <span class="text-surface-200">Rp {{ number_format($doctorFee, 0, ',', '.') }}</span>
                            </div>
                            @foreach($prescriptionItems as $item)
                                <div class="flex justify-between">
                                    <span class="text-surface-400">{{ $item['drug_name'] }} × {{ $item['qty'] }}</span>
                                    <span class="text-surface-200">Rp
                                        {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <div class="border-t border-white/10 pt-2 mt-2 flex justify-between">
                                <span class="font-bold text-surface-200">Total</span>
                                <span
                                    class="text-xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
                                    Rp {{ number_format($this->totalBilling, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Navigation Buttons --}}
    <div class="mt-6 flex items-center justify-between">
        <div>
            @if($step > 1)
                <button wire:click="prevStep"
                    class="px-6 py-2.5 rounded-xl bg-surface-800 text-surface-300 border border-white/10 hover:bg-surface-700 text-sm font-medium transition-all">
                    ← Kembali
                </button>
            @endif
        </div>
        <div>
            @if($step < $totalSteps)
                <button wire:click="nextStep"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-medium hover:bg-primary-500 shadow-lg shadow-primary-500/20 transition-all">
                    Lanjut →
                </button>
            @else
                <button wire:click="saveExamination" wire:loading.attr="disabled"
                    wire:confirm="Simpan pemeriksaan ini? Data tidak dapat diubah setelah disimpan."
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-primary-600 to-accent-500 text-white font-bold shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveExamination">💾 Simpan Pemeriksaan</span>
                    <span wire:loading wire:target="saveExamination">Menyimpan...</span>
                </button>
            @endif
        </div>
    </div>
</div>