<div>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-surface-950/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">ClinicPro</span>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <a href="#features"
                        class="text-sm text-surface-400 hover:text-surface-200 transition-colors">Fitur</a>
                    <a href="#pricing"
                        class="text-sm text-surface-400 hover:text-surface-200 transition-colors">Harga</a>
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 text-sm font-medium bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-lg hover:from-primary-500 hover:to-primary-400 transition-all shadow-lg shadow-primary-500/20">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-400 text-sm font-medium mb-6">
                <span class="w-2 h-2 bg-primary-400 rounded-full animate-pulse"></span>
                Terintegrasi Satu Sehat FHIR
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight">
                <span class="text-surface-100">Rekam Medis</span><br>
                <span
                    class="bg-gradient-to-r from-primary-400 via-accent-400 to-primary-400 bg-clip-text text-transparent">Elektronik
                    Modern</span>
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-surface-400 max-w-2xl mx-auto leading-relaxed">
                Platform SaaS EMR terdepan untuk klinik Indonesia. Kelola pasien, rekam medis, antrian, dan pembayaran —
                semua dalam satu dashboard.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}"
                    class="px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl shadow-2xl shadow-primary-500/25 hover:shadow-primary-500/40 hover:from-primary-500 hover:to-primary-400 transition-all duration-300 text-lg">
                    Mulai Gratis →
                </a>
                <a href="#features"
                    class="px-8 py-4 bg-surface-800/50 text-surface-300 font-semibold rounded-xl border border-white/10 hover:bg-surface-800 hover:border-white/20 transition-all duration-300 text-lg">
                    Lihat Fitur
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-20 grid grid-cols-3 gap-8 max-w-xl mx-auto">
                <div>
                    <p class="text-3xl font-bold text-surface-100">99.9%</p>
                    <p class="text-sm text-surface-500 mt-1">Uptime</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-surface-100">FHIR</p>
                    <p class="text-sm text-surface-500 mt-1">Satu Sehat</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-surface-100">ICD-10</p>
                    <p class="text-sm text-surface-500 mt-1">Standar WHO</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-surface-100">Fitur Unggulan</h2>
                <p class="mt-4 text-surface-400 max-w-2xl mx-auto">Semua yang klinik Anda butuhkan dalam satu platform
                    terintegrasi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature Cards -->
                @php
                    $features = [
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>', 'title' => 'Rekam Medis SOAP', 'desc' => 'Dokumentasi SOAP standar (Subjective, Objective, Assessment, Plan) dengan kode ICD-10.', 'gradient' => 'from-primary-500 to-primary-600'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>', 'title' => 'Manajemen Antrian', 'desc' => 'Sistem antrian real-time per dokter. Pasien check-in, dokter panggil otomatis.', 'gradient' => 'from-accent-500 to-accent-600'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>', 'title' => 'Pembayaran Online', 'desc' => 'Integrasi Midtrans untuk pembayaran fleksibel — kartu kredit, e-wallet, transfer bank.', 'gradient' => 'from-warning-500 to-danger-500'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>', 'title' => 'Satu Sehat (FHIR)', 'desc' => 'Sinkronisasi otomatis ke Satu Sehat: Patient, Encounter, Condition, MedicationRequest.', 'gradient' => 'from-danger-500 to-primary-500'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>', 'title' => 'Dashboard Analitik', 'desc' => 'Bento-style dashboard per role. Monitor kunjungan, pendapatan, dan performa klinik.', 'gradient' => 'from-primary-500 to-accent-500'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>', 'title' => 'Multi-Tenancy', 'desc' => 'Setiap klinik terpisah 100%. Data aman, role-based access untuk admin, dokter, kasir.', 'gradient' => 'from-accent-500 to-primary-500'],
                    ];
                @endphp

                @foreach($features as $feature)
                    <div
                        class="group p-6 rounded-2xl bg-surface-900/50 border border-white/5 hover:border-white/10 hover:bg-surface-900/80 transition-all duration-300">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $feature['gradient'] }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">{!! $feature['icon'] !!}</svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-100 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-surface-400 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 relative">
        <div class="absolute inset-0">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-500/5 rounded-full blur-3xl">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-surface-100">Paket Harga</h2>
                <p class="mt-4 text-surface-400">Pilih paket yang sesuai untuk klinik Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @php
                    $plans = [
                        ['name' => 'Basic', 'price' => '299K', 'per' => '/bulan', 'features' => ['1 Dokter', '500 Pasien', 'Rekam Medis SOAP', 'Antrian Harian', 'Email Support'], 'popular' => false],
                        ['name' => 'Professional', 'price' => '599K', 'per' => '/bulan', 'features' => ['5 Dokter', 'Unlimited Pasien', 'Satu Sehat FHIR', 'Analitik Dashboard', 'Midtrans Payment', 'Priority Support'], 'popular' => true],
                        ['name' => 'Enterprise', 'price' => '999K', 'per' => '/bulan', 'features' => ['Unlimited Dokter', 'Unlimited Pasien', 'Semua Fitur Pro', 'Custom Branding', 'API Access', 'Dedicated Support'], 'popular' => false],
                    ];
                @endphp

                @foreach($plans as $plan)
                    <div
                        class="relative p-8 rounded-2xl {{ $plan['popular'] ? 'bg-gradient-to-b from-primary-500/10 to-surface-900/80 border-primary-500/30' : 'bg-surface-900/50 border-white/5' }} border transition-all hover:scale-[1.02] duration-300">
                        @if($plan['popular'])
                            <div
                                class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-primary-500 to-accent-500 text-white text-xs font-bold rounded-full">
                                POPULAR</div>
                        @endif
                        <h3 class="text-xl font-bold text-surface-200">{{ $plan['name'] }}</h3>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-surface-100">Rp {{ $plan['price'] }}</span>
                            <span class="text-surface-500 text-sm">{{ $plan['per'] }}</span>
                        </div>
                        <ul class="mt-8 space-y-3">
                            @foreach($plan['features'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-surface-300">
                                    <svg class="w-5 h-5 text-accent-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <button
                            class="mt-8 w-full py-3 rounded-xl font-semibold transition-all {{ $plan['popular'] ? 'bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40' : 'bg-surface-800 text-surface-300 border border-white/10 hover:bg-surface-700' }}">
                            Pilih Paket
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <span
                        class="text-lg font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">ClinicPro</span>
                </div>
                <p class="text-sm text-surface-500">&copy; {{ date('Y') }} ClinicPro. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>