<x-layouts.public>
    <x-slot:title>Langganan Habis</x-slot:title>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-md">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-warning-500/10 mb-6">
                <svg class="w-10 h-10 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-surface-100 mb-3">Langganan Telah Habis</h1>
            <p class="text-surface-400 mb-8">Mohon maaf, langganan klinik Anda telah berakhir. Silakan perpanjang untuk
                melanjutkan menggunakan ClinicPro.</p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('landing') }}"
                    class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 transition-all">
                    Lihat Paket Harga
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full px-6 py-3 bg-surface-800 text-surface-300 rounded-xl border border-white/10 hover:bg-surface-700 transition-all">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.public>
