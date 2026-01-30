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
                    <p class="mt-3 text-2xl font-semibold text-white">
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
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Factures émises</p>
                    <p class="mt-3 text-2xl font-semibold text-white">{{ number_format($stats['factures_total'], 0, ',', ' ') }}</p>
                    <p class="mt-1 text-sm text-sky-300">{{ number_format($stats['factures_mois'], 0, ',', ' ') }} ce mois-ci</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Retards</p>
                    <p class="mt-3 text-2xl font-semibold text-white">{{ number_format($stats['retards_total'], 0, ',', ' ') }} factures</p>
                    <p class="mt-1 text-sm text-rose-400">Relances nécessaires</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remises actives</p>
                    <p class="mt-3 text-2xl font-semibold text-white">{{ number_format($stats['remises_actives'], 0, ',', ' ') }} dossiers</p>
                    <p class="mt-1 text-sm text-amber-300">
                        {{ $stats['remises_actives'] > 0 ? 'Remises en cours' : 'Aucune remise active' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            @php($anneeActive = \App\Models\AnneeScolaire::active())
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 xl:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Évolution mensuelle</p>
                        <h2 class="mt-2 text-lg font-semibold text-white">Recettes par mois</h2>
                    </div>
                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">
                        {{ $anneeActive?->libelle ?? 'Année active' }}
                    </span>
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

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Derniers paiements</h3>
                    <a href="{{ route('facturation.index') }}" class="text-sm font-semibold text-emerald-300">Voir tout</a>
                </div>
                <div class="mt-6 space-y-4 text-sm text-slate-300">
                    @php
                        $statutStyles = [
                            'a_jour' => ['label' => 'Payé', 'class' => 'bg-emerald-500/20 text-emerald-300'],
                            'partiel' => ['label' => 'Partiel', 'class' => 'bg-amber-500/20 text-amber-300'],
                            'retard' => ['label' => 'Retard', 'class' => 'bg-rose-500/20 text-rose-300'],
                        ];
                    @endphp
                    @forelse($derniersPaiements as $paiement)
                        @php
                            $eleve = $paiement->eleve;
                            $classe = $eleve?->classe;
                            $statut = $paiement->facture?->statut;
                            $style = $statutStyles[$statut] ?? ['label' => 'Payé', 'class' => 'bg-emerald-500/20 text-emerald-300'];
                            $classeLabel = $classe
                                ? trim(collect([$classe->nom, $classe->niveau])->filter()->implode(' '))
                                : 'Classe non définie';
                        @endphp
                        <div class="flex items-center justify-between rounded-2xl bg-slate-950/60 px-4 py-3">
                            <div>
                                <p class="font-semibold text-white">
                                    {{ trim(($eleve->prenom ?? '').' '.($eleve->nom ?? '')) ?: 'Élève inconnu' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $classeLabel }} · {{ number_format($paiement->montant, 0, ',', ' ') }} XOF
                                </p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $style['class'] }}">{{ $style['label'] }}</span>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-800 px-4 py-6 text-center text-sm text-slate-400">
                            Aucun paiement enregistré pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Alertes & actions</h3>
                    <button class="rounded-full bg-slate-800 px-4 py-1 text-xs font-semibold text-slate-200">Configurer</button>
                </div>
                <div class="mt-6 space-y-4 text-sm text-slate-300">
                    @foreach($alertes as $alerte)
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3">
                            <p class="font-semibold text-white">{{ $alerte['titre'] }}</p>
                            <p class="text-xs text-slate-500">{{ $alerte['detail'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs font-semibold text-rose-600 dark:text-rose-300">Retard</span>
          </div>
        </div>
      </div>
      <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/70 p-6">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Alertes & actions</h3>
          <button class="rounded-full bg-slate-100 dark:bg-slate-800 px-4 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200">Configurer</button>
        </div>
        <div class="mt-6 space-y-4 text-sm text-slate-600 dark:text-slate-300">
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3">
            <p class="font-semibold text-slate-900 dark:text-white">Relances à envoyer</p>
            <p class="text-xs text-slate-500">18 parents à notifier cette semaine.</p>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3">
            <p class="font-semibold text-slate-900 dark:text-white">Tarifs à réviser</p>
            <p class="text-xs text-slate-500">2 classes sans tarif validé pour le mois prochain.</p>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3">
            <p class="font-semibold text-slate-900 dark:text-white">Exports PDF & Excel</p>
            <p class="text-xs text-slate-500">Dernier export généré : 03/09/2024.</p>
          </div>
        </div>
      </div>
    </section>
  </div>

    @push('scripts')
        <script>
            window.dashboardCharts = @json($charts);
        </script>
        @vite('resources/js/dashboard.js')
    @endpush
</x-app-layout>
