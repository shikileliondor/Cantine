<x-app-layout>
    <x-slot name="header">
        Tableau de bord
    </x-slot>

    <div class="space-y-8">
        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl shadow-slate-950/40">
                <p class="text-sm text-slate-400">Bonjour {{ auth()->user()->name ?? 'Responsable' }}</p>
                <h1 class="mt-2 text-2xl font-semibold text-white">Suivi intelligent de la cantine</h1>
                <p class="mt-3 text-sm text-slate-400">
                    Visualisez en un coup d'œil les encaissements, les retards et les remises appliquées.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">Paiements à jour</span>
                    <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-300">Factures partielles</span>
                    <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs font-semibold text-rose-300">Relances en retard</span>
                </div>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:col-span-2">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Encaissements</p>
                    <p class="mt-3 text-2xl font-semibold text-white">24 560 000 XOF</p>
                    <p class="mt-1 text-sm text-emerald-400">+ 12% sur le mois</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Factures émises</p>
                    <p class="mt-3 text-2xl font-semibold text-white">1 280</p>
                    <p class="mt-1 text-sm text-sky-300">98% générées</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Retards</p>
                    <p class="mt-3 text-2xl font-semibold text-white">74 factures</p>
                    <p class="mt-1 text-sm text-rose-400">Relances nécessaires</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remises actives</p>
                    <p class="mt-3 text-2xl font-semibold text-white">18 dossiers</p>
                    <p class="mt-1 text-sm text-amber-300">Paramètre activé</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 xl:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Évolution mensuelle</p>
                        <h2 class="mt-2 text-lg font-semibold text-white">Recettes par mois</h2>
                    </div>
                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">Année active</span>
                </div>
                <div class="mt-6 h-64">
                    <canvas id="cantine-revenus-chart" class="h-full w-full"></canvas>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Statuts factures</p>
                <h2 class="mt-2 text-lg font-semibold text-white">Répartition</h2>
                <div class="mt-6 h-56">
                    <canvas id="cantine-repartition-chart" class="h-full w-full"></canvas>
                </div>
                <div class="mt-6 space-y-3 text-sm text-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>À jour</span>
                        <span>62%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Partiel</span>
                        <span>23%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-400"></span>Retard</span>
                        <span>15%</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Derniers paiements</h3>
                    <a href="#" class="text-sm font-semibold text-emerald-300">Voir tout</a>
                </div>
                <div class="mt-6 space-y-4 text-sm text-slate-300">
                    <div class="flex items-center justify-between rounded-2xl bg-slate-950/60 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">Ndiaye Awa</p>
                            <p class="text-xs text-slate-500">Classe CM2 · 10 000 XOF</p>
                        </div>
                        <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">Payé</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-950/60 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">Diallo Ibrahima</p>
                            <p class="text-xs text-slate-500">Classe CE1 · 6 000 XOF</p>
                        </div>
                        <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-300">Partiel</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-950/60 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">Koné Mariatou</p>
                            <p class="text-xs text-slate-500">Classe 3e · 12 000 XOF</p>
                        </div>
                        <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs font-semibold text-rose-300">Retard</span>
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Alertes & actions</h3>
                    <button class="rounded-full bg-slate-800 px-4 py-1 text-xs font-semibold text-slate-200">Configurer</button>
                </div>
                <div class="mt-6 space-y-4 text-sm text-slate-300">
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3">
                        <p class="font-semibold text-white">Relances à envoyer</p>
                        <p class="text-xs text-slate-500">18 parents à notifier cette semaine.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3">
                        <p class="font-semibold text-white">Tarifs à réviser</p>
                        <p class="text-xs text-slate-500">2 classes sans tarif validé pour le mois prochain.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3">
                        <p class="font-semibold text-white">Exports PDF & Excel</p>
                        <p class="text-xs text-slate-500">Dernier export généré : 03/09/2024.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        @vite('resources/js/dashboard.js')
    @endpush
</x-app-layout>
