<div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-8">
        <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 mb-4 shadow-lg shadow-primary-500/20">
            <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
            ClinicPro</h1>
        <p class="text-surface-500 mt-1 text-sm">Masuk ke akun Anda</p>
    </div>

    <!-- Login Card -->
    <div class="bg-surface-900/60 backdrop-blur-xl border border-white/5 rounded-2xl p-8 shadow-2xl">
        <form wire:submit="login" class="space-y-5">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-surface-300 mb-1.5">Email</label>
                <input wire:model="email" type="email" id="email" placeholder="nama@klinik.com"
                    class="w-full px-4 py-3 bg-surface-800/50 border border-white/10 rounded-xl text-surface-100 placeholder-surface-600 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/40 transition-all duration-200"
                    autofocus>
                @error('email')
                    <p class="mt-1.5 text-sm text-danger-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-surface-300 mb-1.5">Password</label>
                <input wire:model="password" type="password" id="password" placeholder="••••••••"
                    class="w-full px-4 py-3 bg-surface-800/50 border border-white/10 rounded-xl text-surface-100 placeholder-surface-600 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/40 transition-all duration-200">
                @error('password')
                    <p class="mt-1.5 text-sm text-danger-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="remember" type="checkbox"
                        class="w-4 h-4 rounded border-white/20 bg-surface-800 text-primary-500 focus:ring-primary-500/40">
                    <span class="text-sm text-surface-400">Ingat saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full py-3 px-6 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/20 transition-all duration-200 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="login">Masuk</span>
                <span wire:loading wire:target="login" class="flex items-center gap-2">
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </form>
    </div>

    <!-- Demo Credentials -->
    <div class="mt-6 bg-surface-900/40 border border-white/5 rounded-xl p-4">
        <p class="text-xs text-surface-500 font-medium mb-2">Demo Credentials</p>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <button wire:click="$set('email', 'superadmin@clinicpro.test')"
                class="text-left p-2 rounded-lg hover:bg-surface-800 transition-colors text-surface-400 hover:text-surface-200">
                <span class="block font-medium text-primary-400">Superadmin</span>
                superadmin@clinicpro.test
            </button>
            <button wire:click="$set('email', 'admin@kliniksehat.test')"
                class="text-left p-2 rounded-lg hover:bg-surface-800 transition-colors text-surface-400 hover:text-surface-200">
                <span class="block font-medium text-accent-400">Admin</span>
                admin@kliniksehat.test
            </button>
            <button wire:click="$set('email', 'dokter@kliniksehat.test')"
                class="text-left p-2 rounded-lg hover:bg-surface-800 transition-colors text-surface-400 hover:text-surface-200">
                <span class="block font-medium text-warning-500">Dokter</span>
                dokter@kliniksehat.test
            </button>
            <button wire:click="$set('email', 'kasir@kliniksehat.test')"
                class="text-left p-2 rounded-lg hover:bg-surface-800 transition-colors text-surface-400 hover:text-surface-200">
                <span class="block font-medium text-success-500">Kasir</span>
                kasir@kliniksehat.test
            </button>
        </div>
        <p class="text-xs text-surface-600 mt-2">Password: <code class="text-surface-400">password</code></p>
    </div>
</div>
