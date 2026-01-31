<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cantine') }}</title>

        <script>
            const storedTheme = localStorage.getItem('cantine-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cantine.js', 'resources/js/modules/eleves.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        <div class="min-h-screen">
            <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/40 dark:bg-slate-950/70 lg:hidden"></div>

            <aside
                id="sidebar"
                wire:navigate.persist
                class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200 bg-white/90 backdrop-blur transition-transform duration-300 dark:border-slate-800 dark:bg-slate-900/90 lg:translate-x-0"
            >
                <div class="flex h-full flex-col">
                    <div class="flex items-center gap-3 px-6 py-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Cantine</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">Gestion scolaire</p>
                        </div>
                    </div>

                    <nav class="flex-1 space-y-1 px-4">
                        <a wire:navigate href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-2xl bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:bg-slate-800/70 dark:text-white">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-300">
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4.5l9 5.25M4.5 10.5V19.5a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-9" />
                                </svg>
                            </span>
                            Tableau de bord
                            <span class="ml-auto rounded-full bg-emerald-500/20 px-2 py-0.5 text-xs text-emerald-700 dark:text-emerald-300">Actif</span>
                        </a>
                        <div class="space-y-1">
                            <p class="px-4 pt-4 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-500">Gestion</p>
                            <a wire:navigate href="{{ route('eleves.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('eleves.index', 'eleves.create', 'eleves.edit') ? 'bg-slate-100 text-slate-900 dark:bg-slate-800/70 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/60 dark:hover:text-white' }}">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:text-emerald-600 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:text-emerald-300">👧</span>
                                Élèves
                                <span class="ml-auto rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-300">248</span>
                            </a>
                            <a wire:navigate href="{{ route('notes-bulletins') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('notes-bulletins') ? 'bg-slate-100 text-slate-900 dark:bg-slate-800/70 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/60 dark:hover:text-white' }}">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:text-emerald-600 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:text-emerald-300">📘</span>
                                Notes &amp; bulletins
                            </a>
                            <a wire:navigate href="{{ route('tarifs.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('tarifs.*') ? 'bg-slate-100 text-slate-900 dark:bg-slate-800/70 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/60 dark:hover:text-white' }}">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:text-emerald-600 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:text-emerald-300">💶</span>
                                Tarifs
                            </a>
                            <a wire:navigate href="{{ route('facturation.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('facturation.*') ? 'bg-slate-100 text-slate-900 dark:bg-slate-800/70 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/60 dark:hover:text-white' }}">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:text-emerald-600 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:text-emerald-300">🧾</span>
                                Facturation
                                <span class="ml-auto rounded-full bg-emerald-500/20 px-2 py-0.5 text-xs text-emerald-700 dark:text-emerald-300">Unifié</span>
                            </a>
                            <a wire:navigate href="{{ route('parametres.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('parametres.*') ? 'bg-slate-100 text-slate-900 dark:bg-slate-800/70 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/60 dark:hover:text-white' }}">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:text-emerald-600 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:text-emerald-300">⚙️</span>
                                Paramètres
                            </a>
                        </div>
                    </nav>

                    <div class="px-6 pb-6">
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 dark:border-slate-800 dark:bg-slate-900/60">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Année scolaire</p>
                            @php($anneeActive = \App\Models\AnneeScolaire::active())
                            <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $anneeActive?->libelle ?? 'Non définie' }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Devise active : XOF</p>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex min-h-screen flex-col lg:pl-72">
                <header wire:navigate.persist class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                data-sidebar-toggle
                                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-800 dark:text-slate-200 dark:hover:text-emerald-300 lg:hidden"
                            >
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h12" />
                                </svg>
                            </button>
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Résumé</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    @isset($header)
                                        {{ $header }}
                                    @else
                                        Tableau de bord
                                    @endisset
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                data-theme-toggle
                                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-800 dark:text-slate-200 dark:hover:text-emerald-300"
                                aria-label="Basculer le thème clair ou sombre"
                            >
                                <svg viewBox="0 0 24 24" class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v1.5m0 13.5v1.5m8.25-8.25h-1.5m-13.5 0H3.75m15.364-5.114-1.06 1.06M6.696 17.303l-1.06 1.06m12.728 0-1.06-1.06M6.696 6.697l-1.06-1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                                </svg>
                                <svg viewBox="0 0 24 24" class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                                </svg>
                            </button>
                            <div class="hidden text-right text-sm text-slate-600 dark:text-slate-300 sm:block">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name ?? 'Responsable Cantine' }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'contact@cantine.local' }}</p>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-emerald-400/60 to-sky-500/60"></div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-6 pb-12 pt-8">
                    {{ $slot }}
                </main>

                <footer class="border-t border-slate-200 px-6 py-6 text-sm text-slate-500 dark:border-slate-800">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <p>© {{ date('Y') }} Cantine Scolaire. Tous droits réservés.</p>
                        <p>Plateforme prête pour multi-années scolaires.</p>
                    </div>
                </footer>
            </div>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
