<x-slot:sidebar>
    <a href="{{ route('superadmin.dashboard') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('superadmin.dashboard') ? 'bg-primary-500/10 text-primary-400' : 'text-surface-400 hover:text-surface-200 hover:bg-surface-800' }} transition-all">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        Dashboard
    </a>
</x-slot:sidebar>

<x-slot:header>Dashboard Superadmin</x-slot:header>

<div>
    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Clinics -->
        <div
            class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-primary-500/20 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-surface-100">{{ $totalClinics }}</p>
            <p class="text-sm text-surface-500 mt-1">Total Klinik</p>
        </div>

        <!-- Active Clinics -->
        <div
            class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-accent-500/20 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-10 h-10 rounded-xl bg-accent-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-surface-100">{{ $activeClinics }}</p>
            <p class="text-sm text-surface-500 mt-1">Klinik Aktif</p>
        </div>

        <!-- Active Subscriptions -->
        <div
            class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-warning-500/20 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-10 h-10 rounded-xl bg-warning-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-surface-100">{{ $activeSubscriptions }}</p>
            <p class="text-sm text-surface-500 mt-1">Langganan Aktif</p>
        </div>

        <!-- Blocked -->
        <div
            class="p-6 rounded-2xl bg-surface-900/60 border border-white/5 hover:border-danger-500/20 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-10 h-10 rounded-xl bg-danger-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-danger-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-surface-100">{{ $blockedClinics }}</p>
            <p class="text-sm text-surface-500 mt-1">Klinik Diblokir</p>
        </div>
    </div>

    <!-- Recent Clinics -->
    <div class="rounded-2xl bg-surface-900/60 border border-white/5 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="text-lg font-semibold text-surface-200">Klinik Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-surface-500 uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentClinics as $clinic)
                        <tr class="hover:bg-surface-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-surface-200 font-medium">{{ $clinic->name }}</td>
                            <td class="px-6 py-4 text-sm text-surface-400">{{ $clinic->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $clinic->status === 'active' ? 'bg-accent-500/10 text-accent-400' : 'bg-danger-500/10 text-danger-500' }}">
                                    {{ $clinic->status === 'active' ? 'Aktif' : 'Diblokir' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-500">{{ $clinic->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-surface-500 text-sm">Belum ada klinik
                                terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
