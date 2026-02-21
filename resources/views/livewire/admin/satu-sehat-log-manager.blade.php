<div>
    <x-slot name="header">
        Log Integrasi
    </x-slot>

    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header Section (Hero-style) -->
        <div class="relative overflow-hidden bg-white dark:bg-surface-900 rounded-3xl p-6 sm:p-8 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5">
            <!-- Decorative Background Element -->
            <div class="absolute -right-24 -top-24 w-96 h-96 bg-primary-100 dark:bg-primary-500/5 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0 ring-1 ring-inset ring-primary-100 dark:ring-primary-500/20">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">Riwayat Sinkronisasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-accent-500 dark:from-primary-400 dark:to-accent-400">Satu Sehat</span></h1>
                        <p class="text-sm text-surface-500 dark:text-surface-400 max-w-2xl leading-relaxed">Pantau aktivitas pengiriman data rekam medis pasien (Encounter & Diagnosis) dari SIstem EMR Klinik ke API Kemenkes Satu Sehat.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Tools Bar -->
        <div class="bg-white dark:bg-surface-900/60 p-4 sm:p-5 flex flex-col sm:flex-row gap-4 justify-between items-center rounded-2xl shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5">
            <div class="w-full sm:w-auto flex items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4.5 h-4.5 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan Pasien / RM..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-50 dark:bg-surface-950/50 border-none rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-surface-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none transition-shadow">
                </div>
            </div>

            <div class="w-full sm:w-auto flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 hide-scrollbar">
                @php
                    $filters = [
                        'all' => ['label' => 'Semua Data', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                        'success' => ['label' => 'Berhasil', 'icon' => 'M5 13l4 4L19 7'],
                        'failed' => ['label' => 'Gagal', 'icon' => 'M6 18L18 6M6 6l12 12']
                    ];
                @endphp
                @foreach($filters as $val => $data)
                    <button wire:click="$set('statusFilter', '{{ $val }}')"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap {{ $statusFilter === $val 
                            ? 'bg-surface-900 dark:bg-white text-white dark:text-surface-900 shadow-md' 
                            : 'bg-transparent text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800' }}">
                        <svg class="w-4 h-4 {{ $statusFilter === $val ? 'text-white dark:text-surface-900' : 'text-surface-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $data['icon'] }}" />
                        </svg>
                        {{ $data['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Table Data -->
        <div class="bg-white dark:bg-surface-900/60 rounded-3xl shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-surface-500 dark:text-surface-400 uppercase bg-surface-50/80 dark:bg-surface-800/50 border-b border-surface-200 dark:border-white/5 tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-3xl">Waktu & Tipe Resourse</th>
                            <th class="px-6 py-4">Data Pasien</th>
                            <th class="px-6 py-4">Status Integrasi</th>
                            <th class="px-6 py-4">Deskripsi Error</th>
                            <th class="px-6 py-4 text-right rounded-tr-3xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-200/60 dark:divide-white/5">
                        @forelse($logs as $log)
                            <tr class="hover:bg-surface-50/50 dark:hover:bg-surface-800/50 transition-colors group" x-data="{ expanded: false }">
                                <td class="px-6 py-4.5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                            @if(strtolower($log->resource_type) === 'patient')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            @elseif(strtolower($log->resource_type) === 'encounter')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            @elseif(strtolower($log->resource_type) === 'condition')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="block font-bold text-surface-900 dark:text-white">{{ $log->resource_type }}</span>
                                            <span class="text-xs text-surface-500 dark:text-surface-400 font-medium">{{ $log->last_attempted_at?->format('d/m/Y H:i') ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4.5 whitespace-nowrap">
                                    <span class="block font-bold text-surface-900 dark:text-white">{{ $log->medicalRecord->patient->name ?? 'Data Pasien Tidak Ditemukan' }}</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400">{{ $log->medicalRecord->patient->medical_record_no ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4.5">
                                    @if($log->status === 'success')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 border border-success-200 dark:border-success-500/20">
                                            <div class="w-1.5 h-1.5 rounded-full bg-success-500 animate-pulse"></div> Berhasil
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-danger-50 dark:bg-danger-500/10 text-danger-700 dark:text-danger-400 border border-danger-200 dark:border-danger-500/20">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg> Gagal Terkirim
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5">
                                    @if($log->error_message)
                                        <div class="text-xs font-medium text-danger-600 dark:text-danger-400 bg-danger-50 dark:bg-danger-500/5 px-3 py-2 rounded-lg border border-danger-100 dark:border-danger-500/10 line-clamp-2" title="{{ $log->error_message }}">
                                            {{ $log->error_message }}
                                        </div>
                                    @else
                                        <span class="opacity-30">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 text-right">
                                    <button type="button" @click="expanded = !expanded" 
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl transition-all w-full sm:w-auto"
                                        :class="expanded ? 'bg-surface-100 dark:bg-surface-800 text-surface-900 dark:text-white' : 'bg-surface-50 dark:bg-surface-800/50 text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800 border border-surface-200 dark:border-white/5 shadow-sm'">
                                        <span x-text="expanded ? 'Tutup Detail' : 'Log Detail'"></span>
                                        <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Collapsible Detail Section (Mac-like Terminal Window) -->
                            <tr x-show="expanded" style="display: none;" x-transition.opacity.duration.300ms>
                                <td colspan="5" class="p-0 border-b border-surface-200 dark:border-white/5">
                                    <div class="px-6 py-5 bg-surface-50 dark:bg-surface-950/30 border-t border-surface-100 dark:border-white/5 shadow-inner">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <!-- Request Payload -->
                                            <div class="rounded-xl overflow-hidden border border-surface-200 dark:border-white/10 shadow-sm">
                                                <div class="bg-surface-100 dark:bg-surface-900 px-4 py-2.5 border-b border-surface-200 dark:border-white/10 flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                        <span class="text-xs font-bold text-surface-700 dark:text-surface-300 uppercase tracking-widest">Payload Request JSON</span>
                                                    </div>
                                                    <div class="flex gap-1.5">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-danger-400"></div>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-success-400"></div>
                                                    </div>
                                                </div>
                                                <div class="bg-surface-900 p-4 overflow-x-auto max-h-[300px] overflow-y-auto">
                                                    <pre class="text-surface-300 font-mono text-xs leading-relaxed"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                </div>
                                            </div>

                                            <!-- Response Payload -->
                                            <div class="rounded-xl overflow-hidden border border-surface-200 dark:border-white/10 shadow-sm">
                                                <div class="bg-surface-100 dark:bg-surface-900 px-4 py-2.5 border-b border-surface-200 dark:border-white/10 flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-{{ $log->status === 'success' ? 'success' : 'danger' }}-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        <span class="text-xs font-bold text-surface-700 dark:text-surface-300 uppercase tracking-widest">Satu Sehat Response</span>
                                                    </div>
                                                    <div class="flex gap-1.5">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-surface-300 dark:bg-surface-700"></div>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-surface-300 dark:bg-surface-700"></div>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-surface-300 dark:bg-surface-700"></div>
                                                    </div>
                                                </div>
                                                <div class="bg-surface-900 p-4 overflow-x-auto max-h-[300px] overflow-y-auto">
                                                    <pre class="text-surface-300 font-mono text-xs leading-relaxed"><code>{{ json_encode($log->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-16 h-16 rounded-2xl bg-surface-50 dark:bg-surface-800 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-surface-900 dark:text-white">Belum Ada Log Integrasi</h3>
                                            <p class="text-sm text-surface-500 max-w-sm mt-1 mx-auto">Riwayat sinkronisasi data dengan API Satu Sehat akan muncul di sini setelah ada transaksi atau pemeriksaan pasien yang terkirim.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-surface-200 dark:border-white/5 bg-surface-50/50 dark:bg-transparent text-sm">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
