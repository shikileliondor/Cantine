<x-app-layout>
  <x-slot name="header">
    Tableau de bord
  </x-slot>

    <div class="space-y-8">
        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900/60">
                <p class="text-sm text-slate-600 dark:text-slate-400">Bonjour {{ auth()->user()->name ?? 'Responsable' }}</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Suivi intelligent de la cantine</h1>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                    Visualisez en un coup d'œil les encaissements, les retards et les remises appliquées.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">Paiements à jour</span>
                    <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-300">Factures partielles</span>
                    <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs font-semibold text-rose-300">Relances en retard</span>
                </div>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:col-span-2">
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Encaissements</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ number_format($stats['encaissements_total'], 0, ',', ' ') }} XOF
                    </p>
                    <p class="mt-1 text-sm text-emerald-400">
                        @if($stats['encaissements_evolution'] !== null)
                            {{ $stats['encaissements_evolution'] >= 0 ? '+' : '' }}{{ $stats['encaissements_evolution'] }}% sur le mois
                        @else
                            Évolution mensuelle indisponible
                        @endif
                    </p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Factures émises</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['factures_total'], 0, ',', ' ') }}</p>
                    <p class="mt-1 text-sm text-sky-300">{{ number_format($stats['factures_mois'], 0, ',', ' ') }} ce mois-ci</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Retards</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['retards_total'], 0, ',', ' ') }} factures</p>
                    <p class="mt-1 text-sm text-rose-400">Relances nécessaires</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remises actives</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['remises_actives'], 0, ',', ' ') }} dossiers</p>
                    <p class="mt-1 text-sm text-amber-300">
                        {{ $stats['remises_actives'] > 0 ? 'Remises en cours' : 'Aucune remise active' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60 xl:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Évolution mensuelle</p>
                        <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Recettes par mois</h2>
                    </div>
                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">
                        {{ $anneeActive?->libelle ?? 'Année active' }}
                    </span>
                </div>
                <div class="mt-6 h-64">
                    <canvas id="cantine-revenus-chart" class="h-full w-full"></canvas>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Statuts factures</p>
                <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Répartition</h2>
                <div class="mt-6 h-56">
                    <canvas id="cantine-repartition-chart" class="h-full w-full"></canvas>
                </div>
                <div class="mt-6 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>À jour</span>
                        <span>{{ $charts['repartition']['percentages'][0] ?? 0 }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Partiel</span>
                        <span>{{ $charts['repartition']['percentages'][1] ?? 0 }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-400"></span>Retard</span>
                        <span>{{ $charts['repartition']['percentages'][2] ?? 0 }}%</span>
                    </div>
                </div>
            </div>
        </section>


    @push('scripts')
        <script>
            window.dashboardCharts = @json($charts);
        </script>
        @vite('resources/js/dashboard.js')
    @endpush
</x-app-layout>
