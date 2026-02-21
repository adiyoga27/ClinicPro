<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>
    <span class="text-surface-900 dark:text-white font-bold">Pemeriksaan — {{ $patient->name }}</span>
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
                                {{ $step >= $s ? 'bg-primary-600 dark:bg-primary-500 text-white shadow-md shadow-primary-500/25' : 'bg-surface-100 dark:bg-surface-800 text-surface-500 border border-surface-200 dark:border-white/10' }}">
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
                            class="text-[11px] font-bold {{ $step >= $s ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 dark:text-surface-600' }}">{{ $label }}</span>
                    </button>
                    @if($i < count($steps) - 1)
                        <div
                            class="flex-1 h-0.5 mx-2 mt-[-20px] {{ $step > $s ? 'bg-primary-600 dark:bg-primary-500' : 'bg-surface-200 dark:bg-surface-800' }} transition-all">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Patient Info Bar --}}
    <div class="mb-6 p-4 rounded-2xl bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 flex items-center gap-4 shadow-sm dark:shadow-none">
        <div
            class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-lg ring-1 ring-primary-100 dark:ring-surface-800">
            {{ strtoupper(substr($patient->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-surface-900 dark:text-surface-100">{{ $patient->name }}</p>
            <p class="text-xs font-semibold text-surface-500">No. RM: {{ $patient->medical_record_no ?? '-' }} · Antrian
                #{{ $queue->queue_no }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-semibold text-surface-500">Tanggal</p>
            <p class="text-sm font-bold text-surface-900 dark:text-surface-300">{{ today()->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Step Content --}}
    <div class="rounded-2xl bg-white dark:bg-surface-900/60 border border-surface-200 dark:border-white/5 p-6 shadow-sm dark:shadow-none">

        {{-- ═══ Step 1: Vital Signs ═══ --}}
        @if($step === 1)
            <div>
                <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100 mb-1">Screening Umum</h3>
                <p class="text-sm font-medium text-surface-600 dark:text-surface-500 mb-6">Masukkan data vital signs pasien</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" wire:model="height" placeholder="165.0"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Berat Badan (kg)</label>
                        <input type="number" step="0.1" wire:model="weight" placeholder="65.0"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Tekanan Darah (mmHg)</label>
                        <div class="flex items-center gap-2">
                            <input type="number" wire:model="blood_pressure_systolic" placeholder="120"
                                class="flex-1 px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                            <span class="text-surface-400 dark:text-surface-500 font-black">/</span>
                            <input type="number" wire:model="blood_pressure_diastolic" placeholder="80"
                                class="flex-1 px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Suhu (°C)</label>
                        <input type="number" step="0.1" wire:model="temperature" placeholder="36.5"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Denyut Nadi (bpm)</label>
                        <input type="number" wire:model="heart_rate" placeholder="80"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Laju Napas (/min)</label>
                        <input type="number" wire:model="respiratory_rate" placeholder="18"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">SpO2 (%)</label>
                        <input type="number" wire:model="spo2" placeholder="98"
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Catatan Alergi</label>
                        <input type="text" wire:model="allergy_notes" placeholder="Contoh: alergi penisilin, seafood..."
                            class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══ Step 2: Subjective (Keluhan) ═══ --}}
        @if($step === 2)
            <div>
                <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100 mb-1">Keluhan Pasien (Subjective)</h3>
                <p class="text-sm font-medium text-surface-600 dark:text-surface-500 mb-6">Catat keluhan utama pasien</p>

                <div>
                    <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Keluhan Utama <span
                            class="text-danger-600 dark:text-danger-500">*</span></label>
                    <textarea wire:model="subjective" rows="5"
                        placeholder="Contoh: pasien mengeluh demam selama 3 hari, disertai batuk dan pilek..."
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none placeholder-surface-400 dark:placeholder-surface-500"></textarea>
                    @error('subjective') <p class="text-xs font-bold text-danger-600 dark:text-danger-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endif

        {{-- ═══ Step 3: Objective & Diagnosis ═══ --}}
        @if($step === 3)
            <div>
                <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100 mb-1">Pemeriksaan & Diagnosis</h3>
                <p class="text-sm font-medium text-surface-600 dark:text-surface-500 mb-6">Hasil pemeriksaan fisik dan diagnosis ICD-10</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Pemeriksaan Fisik
                            (Objective)</label>
                        <textarea wire:model="objective" rows="3" placeholder="Hasil pemeriksaan fisik..."
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none placeholder-surface-400 dark:placeholder-surface-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Assessment</label>
                        <textarea wire:model="assessment" rows="2" placeholder="Penilaian / kesimpulan klinis..."
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none placeholder-surface-400 dark:placeholder-surface-500"></textarea>
                    </div>

                    {{-- ICD-10 Search --}}
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Diagnosis ICD-10</label>
                        <div class="relative flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" wire:model.live.debounce.300ms="icdSearch"
                                    placeholder="Ketik langsung kode / nama penyakit (misal: demam)..."
                                    class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">

                                @if(count($this->icdResults) > 0)
                                    <div
                                        class="absolute z-30 w-full mt-1 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                        @foreach($this->icdResults as $icd)
                                            <button wire:click="addDiagnosis({{ $icd['id'] }})" type="button"
                                                class="w-full px-4 py-2.5 text-left hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors flex items-center gap-3 border-b border-surface-100 dark:border-white/5 last:border-0">
                                                <span
                                                    class="px-2 py-0.5 rounded bg-primary-100 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 text-xs font-mono font-bold">{{ $icd['code'] }}</span>
                                                <span class="text-sm font-medium text-surface-900 dark:text-surface-300">{{ $icd['name_id'] ?: $icd['name_en'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <button wire:click="openIcdModal" type="button" class="shrink-0 px-4 py-2.5 rounded-xl bg-surface-100 dark:bg-surface-800 border border-surface-200 dark:border-white/10 text-surface-700 dark:text-surface-300 hover:text-surface-900 dark:hover:text-white hover:bg-surface-200 dark:hover:bg-surface-700 hover:border-surface-300 dark:hover:border-surface-600 transition-all text-sm font-bold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Kamus ICD-10
                            </button>
                        </div>

                        {{-- Selected Diagnoses --}}
                        @if(!empty($selectedDiagnoses))
                            <div class="mt-3 space-y-2">
                                @foreach($selectedDiagnoses as $index => $diag)
                                    <div
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/5">
                                        <span
                                            class="px-2 py-0.5 rounded text-xs font-bold {{ $diag['type'] === 'primary' ? 'bg-accent-100 dark:bg-accent-500/10 text-accent-700 dark:text-accent-400' : 'bg-surface-200 dark:bg-surface-700 text-surface-700 dark:text-surface-400' }}">
                                            {{ $diag['type'] === 'primary' ? 'Utama' : 'Sekunder' }}
                                        </span>
                                        <span
                                            class="px-2 py-0.5 rounded bg-primary-100 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 text-xs font-mono font-bold">{{ $diag['code'] }}</span>
                                        <span class="flex-1 text-sm font-medium text-surface-900 dark:text-surface-300">{{ $diag['name'] }}</span>
                                        <button wire:click="removeDiagnosis({{ $index }})"
                                            class="text-surface-400 dark:text-surface-600 hover:text-danger-600 dark:hover:text-danger-500 transition-colors">
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
                <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100 mb-1">Penanganan & Resep Obat</h3>
                <p class="text-sm font-medium text-surface-600 dark:text-surface-500 mb-6">Rencana penanganan dan resep obat</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Rencana Penanganan (Plan)</label>
                        <textarea wire:model="plan" rows="3" placeholder="Rencana terapi, tindakan, edukasi pasien..."
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all resize-none placeholder-surface-400 dark:placeholder-surface-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Service Search --}}
                        <div>
                            <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Tambah Tindakan / Jasa</label>
                            <div class="relative flex gap-2">
                                <div class="flex-1 relative">
                                    <input type="text" wire:model.live.debounce.300ms="serviceSearch"
                                        placeholder="Cari tindakan kesehatan..."
                                        class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">

                                    @if(count($this->serviceResults) > 0)
                                        <div
                                            class="absolute z-30 w-full mt-1 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                            @foreach($this->serviceResults as $srv)
                                                <button wire:click="addService({{ $srv['id'] }})" type="button"
                                                    class="w-full px-4 py-2.5 text-left hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors flex items-center justify-between border-b border-surface-100 dark:border-white/5 last:border-0">
                                                    <span class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $srv['name'] }}</span>
                                                    <span class="text-sm font-black text-accent-600 dark:text-accent-400">Rp
                                                        {{ number_format($srv['price'], 0, ',', '.') }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <button wire:click="openServiceModal" type="button" class="shrink-0 px-4 py-2.5 rounded-xl bg-surface-100 dark:bg-surface-800 border border-surface-200 dark:border-white/10 text-surface-700 dark:text-surface-300 hover:text-surface-900 dark:hover:text-white hover:bg-surface-200 dark:hover:bg-surface-700 hover:border-surface-300 dark:hover:border-surface-600 transition-all text-sm font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Pilih Jasa
                                </button>
                            </div>

                            {{-- Selected Services List --}}
                            @if(!empty($selectedServices))
                                <div class="mt-3 space-y-2">
                                    @foreach($selectedServices as $index => $item)
                                        <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none">
                                            <div>
                                                <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $item['name'] }}</p>
                                                <p class="text-xs font-semibold text-surface-600 dark:text-surface-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            </div>
                                            <button wire:click="removeService({{ $index }})"
                                                class="text-surface-400 dark:text-surface-600 hover:text-danger-600 dark:hover:text-danger-500 transition-colors p-1 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700">
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

                        {{-- Medicine Search --}}
                        <div>
                            <label class="block text-xs font-bold text-surface-700 dark:text-surface-400 mb-1.5">Tambah Obat</label>
                            <div class="relative flex gap-2">
                                <div class="flex-1 relative">
                                    <input type="text" wire:model.live.debounce.300ms="medicineSearch"
                                        placeholder="Cari obat (misal: paracetamol)..."
                                        class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm font-medium focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all placeholder-surface-400 dark:placeholder-surface-500">

                                    @if(count($this->medicineResults) > 0)
                                        <div
                                            class="absolute z-30 w-full mt-1 bg-white dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                            @foreach($this->medicineResults as $med)
                                                <button wire:click="addMedicine({{ $med['id'] }})" type="button"
                                                    class="w-full px-4 py-2.5 text-left hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors flex items-center justify-between border-b border-surface-100 dark:border-white/5 last:border-0">
                                                    <div>
                                                        <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $med['name'] }}</p>
                                                        <p class="text-xs font-semibold text-surface-600 dark:text-surface-500">{{ $med['category'] }} · {{ $med['unit'] }}</p>
                                                    </div>
                                                    <span class="text-sm font-black text-accent-600 dark:text-accent-400">Rp
                                                        {{ number_format($med['price'], 0, ',', '.') }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <button wire:click="openMedicineModal" type="button" class="shrink-0 px-4 py-2.5 rounded-xl bg-surface-100 dark:bg-surface-800 border border-surface-200 dark:border-white/10 text-surface-700 dark:text-surface-300 hover:text-surface-900 dark:hover:text-white hover:bg-surface-200 dark:hover:bg-surface-700 hover:border-surface-300 dark:hover:border-surface-600 transition-all text-sm font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Pilih Obat
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Prescription Items Table --}}
                    @if(!empty($prescriptionItems))
                        <div class="rounded-xl border border-surface-200 dark:border-white/5 overflow-hidden shadow-sm dark:shadow-none">
                            <table class="w-full bg-white dark:bg-transparent">
                                <thead>
                                    <tr
                                        class="text-left text-xs text-surface-600 dark:text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-surface-800/50">
                                        <th class="px-4 py-2.5 font-bold">Obat</th>
                                        <th class="px-4 py-2.5 font-bold">Dosis</th>
                                        <th class="px-4 py-2.5 font-bold">Frekuensi</th>
                                        <th class="px-4 py-2.5 font-bold">Durasi</th>
                                        <th class="px-4 py-2.5 font-bold w-20">Qty</th>
                                        <th class="px-4 py-2.5 font-bold text-right">Subtotal</th>
                                        <th class="px-4 py-2.5 font-bold w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-surface-100 dark:divide-white/5">
                                    @foreach($prescriptionItems as $index => $item)
                                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/30 transition-colors">
                                            <td class="px-4 py-2.5">
                                                <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $item['drug_name'] }}</p>
                                                <p class="text-xs font-semibold text-surface-600 dark:text-surface-500">Rp
                                                    {{ number_format($item['price'], 0, ',', '.') }}/{{ $item['unit'] }}</p>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="text" wire:model="prescriptionItems.{{ $index }}.dosage"
                                                    placeholder="500mg"
                                                    class="w-full px-2 py-1.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-lg text-surface-900 dark:text-surface-200 text-xs focus:border-primary-500 transition-all placeholder-surface-400 dark:placeholder-surface-500 font-medium">
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="text" wire:model="prescriptionItems.{{ $index }}.frequency"
                                                    placeholder="3x sehari"
                                                    class="w-full px-2 py-1.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-lg text-surface-900 dark:text-surface-200 text-xs focus:border-primary-500 transition-all placeholder-surface-400 dark:placeholder-surface-500 font-medium">
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="text" wire:model="prescriptionItems.{{ $index }}.duration"
                                                    placeholder="3 hari"
                                                    class="w-full px-2 py-1.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-lg text-surface-900 dark:text-surface-200 text-xs focus:border-primary-500 transition-all placeholder-surface-400 dark:placeholder-surface-500 font-medium">
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <input type="number" min="1" wire:model.live="prescriptionItems.{{ $index }}.qty"
                                                    class="w-full px-2 py-1.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-white/10 rounded-lg text-surface-900 dark:text-surface-200 text-xs text-center focus:border-primary-500 transition-all font-medium">
                                            </td>
                                            <td class="px-4 py-2.5 text-right">
                                                <span class="text-sm font-black text-surface-900 dark:text-surface-200">Rp
                                                    {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <button wire:click="removeMedicine({{ $index }})"
                                                    class="text-surface-400 dark:text-surface-600 hover:text-danger-600 dark:hover:text-danger-500 transition-colors">
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
                        class="p-4 rounded-xl bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-500/5 dark:to-accent-500/5 border border-primary-200 dark:border-primary-500/10 shadow-sm dark:shadow-none">
                        <div class="space-y-2">
                            @foreach($selectedServices as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-surface-600 dark:text-surface-400">{{ $item['name'] }}</span>
                                    <span class="font-bold text-surface-900 dark:text-surface-200">Rp
                                        {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            @foreach($prescriptionItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-surface-600 dark:text-surface-400">{{ $item['drug_name'] }} × {{ $item['qty'] }}</span>
                                    <span class="font-bold text-surface-900 dark:text-surface-200">Rp
                                        {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <div class="border-t border-primary-200 dark:border-white/10 pt-2 mt-2 flex justify-between">
                                <span class="text-sm font-black text-surface-900 dark:text-surface-200">Total Estimasi</span>
                                <span
                                    class="text-lg font-black bg-gradient-to-r from-primary-600 to-accent-600 dark:from-primary-400 dark:to-accent-400 bg-clip-text text-transparent">
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
                <h3 class="text-lg font-bold text-surface-900 dark:text-surface-100 mb-1">Ringkasan Pemeriksaan</h3>
                <p class="text-sm font-medium text-surface-600 dark:text-surface-500 mb-6">Periksa kembali data sebelum menyimpan</p>

                <div class="space-y-4">
                    {{-- Vital Signs Summary --}}
                    <div class="p-4 rounded-xl bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none">
                        <h4 class="text-xs font-black uppercase tracking-wider text-surface-500 dark:text-surface-400 mb-3">Vital Signs</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                            @if($height)
                                <div><span class="font-semibold text-surface-500">TB:</span> <span class="font-bold text-surface-900 dark:text-surface-200">{{ $height }}
                            cm</span></div> @endif
                            @if($weight)
                                <div><span class="font-semibold text-surface-500">BB:</span> <span class="font-bold text-surface-900 dark:text-surface-200">{{ $weight }}
                            kg</span></div> @endif
                            @if($blood_pressure_systolic)
                                <div><span class="font-semibold text-surface-500">TD:</span> <span
                                        class="font-bold text-surface-900 dark:text-surface-200">{{ $blood_pressure_systolic }}/{{ $blood_pressure_diastolic }}
                            mmHg</span></div> @endif
                            @if($temperature)
                                <div><span class="font-semibold text-surface-500">Suhu:</span> <span
                            class="font-bold text-surface-900 dark:text-surface-200">{{ $temperature }} °C</span></div> @endif
                            @if($heart_rate)
                                <div><span class="font-semibold text-surface-500">Nadi:</span> <span
                            class="font-bold text-surface-900 dark:text-surface-200">{{ $heart_rate }} bpm</span></div> @endif
                            @if($respiratory_rate)
                                <div><span class="font-semibold text-surface-500">RR:</span> <span
                            class="font-bold text-surface-900 dark:text-surface-200">{{ $respiratory_rate }} /min</span></div> @endif
                            @if($spo2)
                                <div><span class="font-semibold text-surface-500">SpO2:</span> <span
                            class="font-bold text-surface-900 dark:text-surface-200">{{ $spo2 }}%</span></div> @endif
                            @if($allergy_notes)
                                <div class="col-span-2"><span class="font-semibold text-surface-500">Alergi:</span> <span
                            class="font-bold text-warning-600 dark:text-warning-400">{{ $allergy_notes }}</span></div> @endif
                        </div>
                    </div>

                    {{-- SOAP Summary --}}
                    <div class="p-4 rounded-xl bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none">
                        <h4 class="text-xs font-black uppercase tracking-wider text-surface-500 dark:text-surface-400 mb-3">SOAP</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-semibold text-surface-500">S:</span> <span
                                    class="font-bold text-surface-900 dark:text-surface-200">{{ $subjective ?: '-' }}</span></div>
                            <div><span class="font-semibold text-surface-500">O:</span> <span
                                    class="font-bold text-surface-900 dark:text-surface-200">{{ $objective ?: '-' }}</span></div>
                            <div><span class="font-semibold text-surface-500">A:</span> <span
                                    class="font-bold text-surface-900 dark:text-surface-200">{{ $assessment ?: '-' }}</span></div>
                            <div><span class="font-semibold text-surface-500">P:</span> <span
                                    class="font-bold text-surface-900 dark:text-surface-200">{{ $plan ?: '-' }}</span></div>
                        </div>
                    </div>

                    {{-- Diagnoses Summary --}}
                    @if(!empty($selectedDiagnoses))
                        <div class="p-4 rounded-xl bg-surface-50/50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none">
                            <h4 class="text-xs font-black uppercase tracking-wider text-surface-500 dark:text-surface-400 mb-3">Diagnosis</h4>
                            <div class="space-y-1.5">
                                @foreach($selectedDiagnoses as $diag)
                                    <div class="flex items-center gap-2 text-sm">
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $diag['type'] === 'primary' ? 'bg-accent-100 dark:bg-accent-500/10 text-accent-700 dark:text-accent-400' : 'bg-surface-200 dark:bg-surface-700 text-surface-700 dark:text-surface-400' }}">{{ $diag['type'] === 'primary' ? 'P' : 'S' }}</span>
                                        <span class="font-mono font-bold text-primary-700 dark:text-primary-400">{{ $diag['code'] }}</span>
                                        <span class="font-medium text-surface-900 dark:text-surface-300">{{ $diag['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Billing Summary --}}
                    <div
                        class="p-4 rounded-xl bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-500/5 dark:to-accent-500/5 border border-primary-200 dark:border-primary-500/10 shadow-sm dark:shadow-none">
                        <h4 class="text-xs font-black uppercase tracking-wider text-surface-500 dark:text-surface-400 mb-3">Rincian Biaya</h4>
                        <div class="space-y-1.5 text-sm">
                            @foreach($selectedServices as $item)
                                <div class="flex justify-between">
                                    <span class="font-medium text-surface-600 dark:text-surface-400">{{ $item['name'] }}</span>
                                    <span class="font-bold text-surface-900 dark:text-surface-200">Rp
                                        {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            @foreach($prescriptionItems as $item)
                                <div class="flex justify-between">
                                    <span class="font-medium text-surface-600 dark:text-surface-400">{{ $item['drug_name'] }} × {{ $item['qty'] }}</span>
                                    <span class="font-bold text-surface-900 dark:text-surface-200">Rp
                                        {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <div class="border-t border-primary-200 dark:border-white/10 pt-2 mt-2 flex justify-between">
                                <span class="font-black text-surface-900 dark:text-surface-200">Total</span>
                                <span
                                    class="text-xl font-black bg-gradient-to-r from-primary-600 to-accent-600 dark:from-primary-400 dark:to-accent-400 bg-clip-text text-transparent">
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
                    class="px-6 py-2.5 rounded-xl bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-300 border border-surface-200 dark:border-white/10 hover:bg-surface-50 dark:hover:bg-surface-700 text-sm font-bold transition-all shadow-sm dark:shadow-none">
                    ← Kembali
                </button>
            @endif
        </div>
        <div>
            @if($step < $totalSteps)
                <button wire:click="nextStep"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-bold hover:bg-primary-500 shadow-md shadow-primary-500/20 transition-all">
                    Lanjut →
                </button>
            @else
                <button wire:click="saveExamination" wire:loading.attr="disabled"
                    wire:confirm="Simpan pemeriksaan ini? Data tidak dapat diubah setelah disimpan."
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-primary-600 to-accent-600 dark:from-primary-600 dark:to-accent-500 text-white font-black shadow-md shadow-primary-500/20 hover:shadow-primary-500/40 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveExamination">💾 Simpan Pemeriksaan</span>
                    <span wire:loading wire:target="saveExamination">Menyimpan...</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ICD-10 Dictionary Modal --}}
    @if($showIcdModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="absolute inset-0 bg-surface-900/60 dark:bg-black/60 backdrop-blur-sm" wire:click="$set('showIcdModal', false)"></div>
            <div class="relative bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between border-b border-surface-200 dark:border-white/10 bg-surface-50 dark:bg-surface-900/50">
                    <div>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white">Kamus ICD-10</h3>
                        <p class="text-sm font-medium text-surface-600 dark:text-surface-500">Cari dan pilih diagnosis resmi (WHO)</p>
                    </div>
                    <button wire:click="$set('showIcdModal', false)" class="text-surface-500 hover:text-surface-900 dark:hover:text-white transition-colors bg-surface-100 dark:bg-surface-800 p-2 rounded-full hover:bg-surface-200 dark:hover:bg-surface-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 border-b border-surface-200 dark:border-white/10 bg-surface-50/50 dark:bg-surface-800/30">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.500ms="icdModalSearch" placeholder="Cari kode (J06) atau nama penyakit (Respiratory)..."
                            class="w-full pl-10 pr-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-medium placeholder-surface-400 dark:placeholder-surface-600">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-2 bg-white dark:bg-surface-900">
                    @if(strlen($icdModalSearch) >= 2)
                        @if(count($icdModalResults) > 0)
                            <div class="grid grid-cols-1 gap-1">
                                    @foreach($icdModalResults as $icd)
                                        <div class="group flex items-center justify-between p-3 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-800/80 transition-all border border-transparent hover:border-surface-200 dark:hover:border-white/5">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="w-12 h-10 rounded-lg bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-mono font-bold text-sm flex items-center justify-center shrink-0 border border-primary-100 dark:border-primary-500/20">
                                                    {{ $icd['code'] }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $icd['name_id'] ?: $icd['name_en'] }}</p>
                                                    @if($icd['name_id'] && $icd['name_en'])
                                                        <p class="text-xs font-medium text-surface-500 italic">{{ $icd['name_en'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Check if already selected -->
                                            @php
                                                $isSelected = collect($selectedDiagnoses)->contains('id', $icd['id']);
                                            @endphp
                                            <button wire:click="addDiagnosis({{ $icd['id'] }})" @if($isSelected) disabled @endif
                                                class="shrink-0 ml-4 px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $isSelected ? 'bg-surface-100 dark:bg-surface-800 text-surface-400 dark:text-surface-600 cursor-not-allowed hidden sm:block' : 'bg-surface-50 dark:bg-surface-800 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-500/20 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500 dark:hover:text-white' }}">
                                                {{ $isSelected ? 'Terpilih' : 'Pilih' }}
                                            </button>
                                        </div>
                                    @endforeach
                            </div>
                        @else
                            <div class="h-40 flex flex-col items-center justify-center text-surface-500 gap-2">
                                <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-sm font-medium">Tidak ditemukan kode ICD-10 untuk pencarian tersebut.</p>
                            </div>
                        @endif
                    @else
                        <div class="h-64 flex flex-col items-center justify-center text-surface-500 gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-surface-50 dark:bg-surface-800/50 flex items-center justify-center">
                                <svg class="w-8 h-8 text-surface-400 dark:text-surface-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium">Ketik setidaknya 2 karakter untuk memulai pencarian...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Service Search Modal --}}
    @if($showServiceModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="absolute inset-0 bg-surface-900/60 dark:bg-black/60 backdrop-blur-sm" wire:click="$set('showServiceModal', false)"></div>
            <div class="relative bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between border-b border-surface-200 dark:border-white/10 bg-surface-50 dark:bg-surface-900/50">
                    <div>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white">Pilih Tindakan / Jasa Medis</h3>
                        <p class="text-sm font-medium text-surface-600 dark:text-surface-500">Cari layanan kesehatan dan tindakan medis</p>
                    </div>
                    <button wire:click="$set('showServiceModal', false)" class="text-surface-500 hover:text-surface-900 dark:hover:text-white transition-colors bg-surface-100 dark:bg-surface-800 p-2 rounded-full hover:bg-surface-200 dark:hover:bg-surface-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 border-b border-surface-200 dark:border-white/10 bg-surface-50/50 dark:bg-surface-800/30">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.500ms="serviceModalSearch" placeholder="Cari nama tindakan/jasa..."
                            class="w-full pl-10 pr-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-medium placeholder-surface-400 dark:placeholder-surface-600">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-2 bg-white dark:bg-surface-900">
                    @if(strlen($serviceModalSearch) >= 2)
                        @if(count($serviceModalResults) > 0)
                            <div class="grid grid-cols-1 gap-1">
                                @foreach($serviceModalResults as $srv)
                                    <div class="group flex items-center justify-between p-3 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-800/80 transition-all border border-transparent hover:border-surface-200 dark:hover:border-white/5">
                                        <div class="flex flex-col gap-0.5 flex-1">
                                            <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $srv['name'] }}</p>
                                            <p class="text-xs font-black text-accent-600 dark:text-accent-400">Rp {{ number_format($srv['price'], 0, ',', '.') }}</p>
                                        </div>
                                        @php
                                            $isSelected = collect($selectedServices)->contains('service_id', $srv['id']);
                                        @endphp
                                        <button wire:click="addService({{ $srv['id'] }})" @if($isSelected) disabled @endif
                                            class="shrink-0 ml-4 px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $isSelected ? 'bg-surface-100 dark:bg-surface-800 text-surface-400 dark:text-surface-600 cursor-not-allowed hidden sm:block' : 'bg-surface-50 dark:bg-surface-800 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-500/20 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500 dark:hover:text-white' }}">
                                            {{ $isSelected ? 'Terpilih' : 'Pilih' }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-40 flex flex-col items-center justify-center text-surface-500 gap-2">
                                <p class="text-sm font-medium">Tidak ditemukan tindakan layanan untuk pencarian tersebut.</p>
                            </div>
                        @endif
                    @else
                        <div class="h-64 flex flex-col items-center justify-center text-surface-500 gap-3">
                            <p class="text-sm font-medium">Ketik setidaknya 2 karakter untuk memulai pencarian...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Medicine Search Modal --}}
    @if($showMedicineModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="absolute inset-0 bg-surface-900/60 dark:bg-black/60 backdrop-blur-sm" wire:click="$set('showMedicineModal', false)"></div>
            <div class="relative bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between border-b border-surface-200 dark:border-white/10 bg-surface-50 dark:bg-surface-900/50">
                    <div>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white">Pilih Obat (Farmasi)</h3>
                        <p class="text-sm font-medium text-surface-600 dark:text-surface-500">Cari dan tambahkan obat ke dalam resep</p>
                    </div>
                    <button wire:click="$set('showMedicineModal', false)" class="text-surface-500 hover:text-surface-900 dark:hover:text-white transition-colors bg-surface-100 dark:bg-surface-800 p-2 rounded-full hover:bg-surface-200 dark:hover:bg-surface-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 border-b border-surface-200 dark:border-white/10 bg-surface-50/50 dark:bg-surface-800/30">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.500ms="medicineModalSearch" placeholder="Cari nama obat atau generik..."
                            class="w-full pl-10 pr-4 py-3 bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-xl text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-medium placeholder-surface-400 dark:placeholder-surface-600">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-2 bg-white dark:bg-surface-900">
                    @if(strlen($medicineModalSearch) >= 2)
                        @if(count($medicineModalResults) > 0)
                            <div class="grid grid-cols-1 gap-1">
                                @foreach($medicineModalResults as $med)
                                    <div class="group flex items-center justify-between p-3 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-800/80 transition-all border border-transparent hover:border-surface-200 dark:hover:border-white/5">
                                        <div class="flex flex-col gap-0.5 flex-1">
                                            <p class="text-sm font-bold text-surface-900 dark:text-surface-200">{{ $med['name'] }}</p>
                                            <p class="text-xs font-semibold text-surface-500">{{ $med['category'] }} · {{ $med['unit'] }}</p>
                                        </div>
                                        <div class="text-right flex items-center gap-4">
                                            <span class="text-sm font-black text-accent-600 dark:text-accent-400">Rp {{ number_format($med['price'], 0, ',', '.') }}</span>
                                            @php
                                                $isSelected = collect($prescriptionItems)->contains('medicine_id', $med['id']);
                                            @endphp
                                            <button wire:click="addMedicine({{ $med['id'] }})" @if($isSelected) disabled @endif
                                                class="shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $isSelected ? 'bg-surface-100 dark:bg-surface-800 text-surface-400 dark:text-surface-600 cursor-not-allowed hidden sm:block' : 'bg-surface-50 dark:bg-surface-800 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-500/20 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500 dark:hover:text-white' }}">
                                                {{ $isSelected ? 'Tertambah' : 'Tambah' }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-40 flex flex-col items-center justify-center text-surface-500 gap-2">
                                <p class="text-sm font-medium">Tidak ditemukan obat untuk pencarian tersebut.</p>
                            </div>
                        @endif
                    @else
                        <div class="h-64 flex flex-col items-center justify-center text-surface-500 gap-3">
                            <p class="text-sm font-medium">Ketik setidaknya 2 karakter untuk memulai pencarian...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>