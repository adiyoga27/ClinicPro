<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ (!auth()->check() || auth()->user()->theme === 'dark') ? 'dark' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ClinicPro' }} — ClinicPro EMR</title>
    <meta name="description" content="Sistem Rekam Medis Elektronik modern untuk klinik Indonesia">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-surface-50 dark:bg-surface-950 text-surface-900 dark:text-surface-100 font-sans antialiased min-h-screen">
    <!-- Sidebar + Content -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-surface-900/80 dark:backdrop-blur-xl border-r border-surface-200 dark:border-white/5 shadow-sm dark:shadow-none transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center gap-3 px-6 h-16 border-b border-surface-200 dark:border-white/5">
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

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    {{ $sidebar ?? '' }}
                </nav>

                <!-- User Info -->
                <div class="px-4 py-3 border-t border-surface-200 dark:border-white/5">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500/30 to-accent-500/30 flex items-center justify-center text-sm font-semibold text-primary-300">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-surface-900 dark:text-surface-200 truncate">{{ auth()->user()->name ?? '' }}
                            </p>
                            <p class="text-xs text-surface-500 truncate">
                                {{ auth()->user()->roles->first()?->name ?? '' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="p-1.5 rounded-lg text-surface-400 hover:text-danger-600 dark:hover:text-danger-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors"
                                title="Logout">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 z-20 bg-black/50 hidden lg:hidden" onclick="toggleSidebar()">
        </div>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64">
            <!-- Top Bar -->
            <header
                class="sticky top-0 z-10 h-16 bg-white/80 dark:bg-surface-950/80 backdrop-blur-md dark:backdrop-blur-xl border-b border-surface-200 dark:border-white/5 flex items-center justify-between px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()"
                        class="lg:hidden p-2 rounded-lg text-surface-400 hover:text-surface-900 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-surface-900 dark:text-surface-200">{{ $header ?? '' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    {{ $actions ?? '' }}
                    <form method="POST" action="{{ route('theme.toggle') }}" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-surface-400 hover:text-primary-600 dark:hover:text-primary-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors" title="Toggle Theme">
                            @if(auth()->check() && auth()->user()->theme === 'dark')
                                <!-- Moon (Dark Mode active, click to light) -->
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                            @else
                                <!-- Sun (Light Mode active, click to dark) -->
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 lg:p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div
                        class="mb-4 p-4 rounded-xl bg-success-500/10 border border-success-500/20 text-success-500 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="mb-4 p-4 rounded-xl bg-danger-500/10 border border-danger-500/20 text-danger-500 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>