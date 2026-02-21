<x-slot:sidebar>
    @include('partials.sidebar')
</x-slot:sidebar>

<x-slot:header>Dashboard Admin</x-slot:header>

<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div
            class="p-6 rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 hover:border-primary-300 dark:hover:border-primary-500/20 transition-all group">
            <div
                class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-surface-900 dark:text-surface-100">{{ $todayQueue }}</p>
            <p class="text-sm font-medium text-surface-500 mt-1">Antrian Hari Ini</p>
        </div>

        <div
            class="p-6 rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 hover:border-accent-300 dark:hover:border-accent-500/20 transition-all group">
            <div
                class="w-10 h-10 rounded-xl bg-accent-50 dark:bg-accent-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-surface-900 dark:text-surface-100">{{ $totalPatients }}</p>
            <p class="text-sm font-medium text-surface-500 mt-1">Total Pasien</p>
        </div>

        <div
            class="p-6 rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 hover:border-success-300 dark:hover:border-success-500/20 transition-all group">
            <div
                class="w-10 h-10 rounded-xl bg-success-50 dark:bg-success-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-success-600 dark:text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-surface-900 dark:text-surface-100">{{ $todayVisits }}</p>
            <p class="text-sm font-medium text-surface-500 mt-1">Selesai Hari Ini</p>
        </div>

        <div
            class="p-6 rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 hover:border-warning-300 dark:hover:border-warning-500/20 transition-all group">
            <div
                class="w-10 h-10 rounded-xl bg-warning-50 dark:bg-warning-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-warning-600 dark:text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-surface-900 dark:text-surface-100">{{ $staffCount }}</p>
            <p class="text-sm font-medium text-surface-500 mt-1">Total User</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-surface-900/60 shadow-sm dark:shadow-none border border-surface-200 dark:border-white/5 p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-surface-400 dark:text-surface-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <h3 class="text-lg font-bold text-surface-900 dark:text-surface-300 mb-2">Selamat datang di ClinicPro!</h3>
        <p class="text-sm font-medium text-surface-500">Kelola pasien, antrian, dan staf klinik Anda dari sini.</p>
    </div>
</div>