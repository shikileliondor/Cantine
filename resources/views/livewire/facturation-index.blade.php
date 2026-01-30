<div class="space-y-6" x-data>
  @php
    $factureSelectionnee = $this->factureSelectionnee;
  @endphp
  <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6 shadow-lg shadow-slate-950/40">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Vue simplifiée</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Facturation & paiements</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
          Chaque élève apparaît une seule fois pour saisir les versements, remises et suivre le solde.
        </p>
      </div>
      <details class="relative">
        <summary class="flex cursor-pointer list-none items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-100/80 dark:bg-slate-800/70 px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 transition hover:bg-slate-100 dark:hover:bg-slate-800">
          ⋮
        </summary>
        <div class="absolute right-0 mt-2 w-44 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 p-2 text-sm text-slate-700 dark:text-slate-200 shadow-lg">
          @if ($factureSelectionnee && $factureSelectionnee['id'])
            <a
              href="{{ route('facturation.export.pdf', $factureSelectionnee['id']) }}"
              class="block w-full rounded-xl px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-800"
            >
              Exporter PDF
            </a>
            <a
              href="{{ route('facturation.export.csv', $factureSelectionnee['id']) }}"
              class="mt-1 block w-full rounded-xl px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-800"
            >
              Exporter CSV
            </a>
          @else
            <span class="block w-full cursor-not-allowed rounded-xl px-3 py-2 text-left text-slate-400">Exporter PDF</span>
            <span class="mt-1 block w-full cursor-not-allowed rounded-xl px-3 py-2 text-left text-slate-400">Exporter CSV</span>
          @endif
        </div>
      </details>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
    <div class="grid gap-4 md:grid-cols-2">
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Période</label>
        <div class="mt-2 grid gap-2 sm:grid-cols-2">
          <input wire:model="periodeDebut" type="month" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
          <input wire:model="periodeFin" type="month" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
        </div>
      </div>
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Élève</label>
        <input wire:model.debounce.300ms="eleveFilter" type="text" placeholder="Nom ou prénom" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
      </div>
    </div>
  </section>

  <section class="space-y-6">
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
      <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Élèves</p>
      <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $this->factures->count() }} élève(s)</h2>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
      @forelse ($this->factures as $facture)
        @php
          $isActive = $this->factureSelectionneeId === $facture['id'];
        @endphp
        @php
          $hasFacture = ! empty($facture['id']);
          $actionBase = 'rounded-2xl px-4 py-2 text-center font-semibold transition';
          $actionDisabled = 'opacity-100 cursor-default';
        @endphp
        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/70 p-4">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</p>
              <p class="text-xs text-slate-600 dark:text-slate-400">
                {{ $facture['id'] ? 'Dernière facture : ' . $facture['periode'] : 'Aucune facture enregistrée' }}
              </p>
            </div>
          </div>
          @if ($hasFacture)
            <div class="mt-4 grid gap-3 text-xs text-slate-600 dark:text-slate-400 sm:grid-cols-2">
              <div>
                <p>Net à payer</p>
                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA</p>
              </div>
              <div>
                <p>Reste à payer</p>
                <p class="text-sm font-semibold text-rose-700 dark:text-rose-200">{{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA</p>
              </div>
            </div>
            <div class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-versement'))"
                class="rounded-2xl border border-emerald-500/40 bg-emerald-500/20 px-4 py-2 text-center font-semibold text-emerald-700 dark:text-emerald-100 transition hover:bg-emerald-500/30"
              >
                Ajouter un versement
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-remise'))"
                class="rounded-2xl border border-amber-500/40 bg-amber-500/20 px-4 py-2 text-center font-semibold text-amber-700 dark:text-amber-100 transition hover:bg-amber-500/30"
              >
                Ajouter une remise
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-historique'))"
                class="rounded-2xl border border-slate-200 px-4 py-2 text-center font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                Voir l'historique
              </button>
              <a
                href="{{ route('facturation.export.pdf', $facture['id']) }}"
                class="rounded-2xl border border-slate-900 bg-slate-900 px-4 py-2 text-center font-semibold text-white transition hover:bg-slate-800"
                target="_blank"
                rel="noopener noreferrer"
              >
                Exporter la fiche facture
              </a>
            </div>
            @if ($isActive)
              <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-300">Facture sélectionnée</p>
            @endif
          @else
            <div class="mt-4 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-950/40 px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
              Créez une facture pour commencer à enregistrer les versements et remises.
            </div>
          @endif
          <div class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
            @if ($hasFacture)
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-versement'))"
                class="{{ $actionBase }} border border-emerald-500/40 bg-emerald-500/20 text-emerald-700 dark:text-emerald-100 hover:bg-emerald-500/30"
              >
                Ajouter un versement
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-remise'))"
                class="{{ $actionBase }} border border-amber-500/40 bg-amber-500/20 text-amber-700 dark:text-amber-100 hover:bg-amber-500/30"
              >
                Ajouter une remise
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-historique'))"
                class="{{ $actionBase }} border border-slate-200 text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                Voir l'historique
              </button>
              <a
                href="{{ route('facturation.export.pdf', $facture['id']) }}"
                class="{{ $actionBase }} border border-slate-900 bg-slate-900 text-white hover:bg-slate-800"
                target="_blank"
                rel="noopener noreferrer"
              >
                Exporter la fiche facture
              </a>
            @else
              <span class="{{ $actionBase }} {{ $actionDisabled }} border border-emerald-500/40 bg-emerald-500/10 text-emerald-700 dark:text-emerald-100">
                Ajouter un versement
              </span>
              <span class="{{ $actionBase }} {{ $actionDisabled }} border border-amber-500/40 bg-amber-500/10 text-amber-700 dark:text-amber-100">
                Ajouter une remise
              </span>
              <span class="{{ $actionBase }} {{ $actionDisabled }} border border-slate-200 text-slate-700 dark:border-slate-700 dark:text-slate-200">
                Voir l'historique
              </span>
              <span class="{{ $actionBase }} {{ $actionDisabled }} border border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                Exporter la fiche facture
              </span>
            @endif
          </div>
        </div>
      @empty
        <div class="rounded-3xl border border-dashed border-slate-200 dark:border-slate-700 p-6 text-center text-sm text-slate-600 dark:text-slate-400">
          Aucune facture ne correspond aux filtres.
        </div>
      @endforelse
    </div>

    @if ($this->factureSelectionnee)
      @php
        $facture = $this->factureSelectionnee;
        $eleveSelectionne = $this->eleveSelectionne;
        $progression = $facture['net_a_payer'] > 0 ? min(100, (int) round(($facture['total_verse'] / $facture['net_a_payer']) * 100)) : 0;
      @endphp
      <div id="facture-detail" class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Détail facture</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Période : {{ $facture['periode'] }}</p>
          </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Montant brut</p>
            <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ number_format($facture['montant_brut'], 0, ',', ' ') }} FCFA</p>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total remises</p>
            <p class="mt-1 text-lg font-semibold text-amber-700 dark:text-amber-200">{{ number_format($facture['total_remises'], 0, ',', ' ') }} FCFA</p>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Net à payer</p>
            <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA</p>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total versé</p>
            <p class="mt-1 text-lg font-semibold text-emerald-700 dark:text-emerald-200">{{ number_format($facture['total_verse'], 0, ',', ' ') }} FCFA</p>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Reste à payer</p>
            <p class="mt-1 text-lg font-semibold text-rose-700 dark:text-rose-200">{{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA</p>
          </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-3">
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Versement express</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Encaissez le reste à payer en un clic pour éviter les doubles saisies.
            </p>
            <button
              type="button"
              wire:click="encaisserSolde"
              class="mt-4 w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-200 transition hover:bg-emerald-500/30"
            >
              Encaisser le reste
            </button>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remise express</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Appliquez rapidement une remise standard sans ouvrir le formulaire complet.
            </p>
            <div class="mt-3 grid gap-3 sm:grid-cols-[140px_1fr]">
              <select wire:model="remiseRapideType" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100">
                <option value="pourcentage">Pourcentage</option>
                <option value="montant">Montant fixe</option>
              </select>
              <input wire:model="remiseRapideValeur" type="number" min="0" placeholder="Valeur" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
            </div>
            <button
              type="button"
              wire:click="appliquerRemiseRapide"
              class="mt-4 w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-700 dark:text-amber-200 transition hover:bg-amber-500/30"
            >
              Appliquer la remise
            </button>
          </div>
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Exports rapides</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Téléchargez la facture ou un récapitulatif CSV sans quitter l'onglet.
            </p>
            <div class="mt-4 space-y-2">
              <a
                href="{{ route('facturation.export.pdf', $facture['id']) }}"
                class="block w-full rounded-2xl bg-slate-900 px-4 py-2 text-center text-sm font-semibold text-white transition hover:bg-slate-800"
              >
                Exporter PDF
              </a>
              <a
                href="{{ route('facturation.export.csv', $facture['id']) }}"
                class="block w-full rounded-2xl border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                Exporter CSV
              </a>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
            <span>Progression du paiement</span>
            <span>{{ $progression }}%</span>
          </div>
          <div class="mt-2 h-2 rounded-full bg-slate-100 dark:bg-slate-800">
            <div class="h-2 rounded-full bg-emerald-500/70" style="width: {{ $progression }}%"></div>
          </div>
        </div>

        <div class="mt-6 space-y-3">
          <details class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <summary class="cursor-pointer list-none text-sm font-semibold text-slate-900 dark:text-white">Versements</summary>
            <div class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-200">
              <div class="space-y-3">
                @forelse ($facture['versements'] as $versement)
                  <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-3">
                    <div class="flex items-center justify-between">
                      <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($versement['montant'], 0, ',', ' ') }} FCFA</p>
                      <span class="text-xs text-slate-600 dark:text-slate-400">{{ $versement['date'] }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ ucfirst(str_replace('_', ' ', $versement['mode'])) }} · {{ $versement['reference'] ?? '—' }}</p>
                    @if (!empty($versement['commentaire']))
                      <p class="text-xs text-slate-500">{{ $versement['commentaire'] }}</p>
                    @endif
                  </div>
                @empty
                  <p class="text-slate-600 dark:text-slate-400">Aucun versement enregistré.</p>
                @endforelse
              </div>
              <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ajouter un versement</p>
                <div class="grid gap-3 sm:grid-cols-2">
                  <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                  <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                </div>
                <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100">
                  <option value="especes">Espèces</option>
                  <option value="mobile_money">Mobile money</option>
                  <option value="virement">Virement</option>
                  <option value="carte">Carte</option>
                </select>
                <details class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2">
                  <summary class="cursor-pointer list-none text-xs uppercase tracking-[0.2em] text-slate-500">Champs avancés</summary>
                  <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <input wire:model="versementForm.reference" type="text" placeholder="Référence (optionnel)" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                    <input wire:model="versementForm.commentaire" type="text" placeholder="Commentaire" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                  </div>
                </details>
                <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-200 transition hover:bg-emerald-500/30">
                  Enregistrer le versement
                </button>
              </div>
            </div>
          </details>

          <details class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <summary class="cursor-pointer list-none text-sm font-semibold text-slate-900 dark:text-white">Remises</summary>
            <div class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-200">
              <div class="space-y-3">
                @forelse ($facture['remises'] as $remise)
                  <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-3">
                    <p class="font-semibold text-slate-700 dark:text-slate-100">
                      {{ $remise['type'] === 'pourcentage' ? $remise['valeur'] . '%' : number_format($remise['valeur'], 0, ',', ' ') . ' FCFA' }}
                    </p>
                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $remise['commentaire'] ?: 'Remise appliquée automatiquement' }}</p>
                  </div>
                @empty
                  <p class="text-slate-600 dark:text-slate-400">Aucune remise appliquée pour cette facture.</p>
                @endforelse
              </div>
              <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Appliquer une remise</p>
                <div class="grid gap-3 sm:grid-cols-2">
                  <select wire:model="remiseForm.type" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100">
                    <option value="pourcentage">Pourcentage</option>
                    <option value="montant">Montant fixe</option>
                  </select>
                  <input wire:model="remiseForm.valeur" type="number" min="0" placeholder="Valeur" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                </div>
                <input wire:model="remiseForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                <button wire:click="ajouterRemise" type="button" class="w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-700 dark:text-amber-200 transition hover:bg-amber-500/30">
                  Appliquer la remise
                </button>
              </div>
            </div>
          </details>

          <details class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
            <summary class="cursor-pointer list-none text-sm font-semibold text-slate-900 dark:text-white">Historique</summary>
            <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-200">
              @if ($eleveSelectionne)
                @foreach ($eleveSelectionne['factures'] as $factureEleve)
                  <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-3">
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $factureEleve['periode'] }}</p>
                    <p class="text-xs text-slate-600 dark:text-slate-400">
                      Net : {{ number_format($factureEleve['net_a_payer'], 0, ',', ' ') }} FCFA ·
                      Versé : {{ number_format($factureEleve['total_verse'], 0, ',', ' ') }} FCFA ·
                      Reste : {{ number_format($factureEleve['reste_a_payer'], 0, ',', ' ') }} FCFA
                    </p>
                    @php
                      $versements = $factureEleve['versements'] ?? [];
                      $remises = $factureEleve['remises'] ?? [];
                      $hasMouvements = count($versements) || count($remises);
                    @endphp
                    <div class="mt-3 space-y-2 text-xs text-slate-600 dark:text-slate-300">
                      @if (! $hasMouvements)
                        <p class="text-slate-500">Aucun mouvement enregistré pour cette période.</p>
                      @endif
                      @foreach ($versements as $versement)
                        <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/40 px-3 py-2">
                          <div>
                            <p class="font-semibold text-slate-700 dark:text-slate-100">Versement</p>
                            <p class="text-slate-600 dark:text-slate-400">
                              {{ $versement['date'] ?? 'Date inconnue' }} ·
                              {{ $versement['mode'] ? str_replace('_', ' ', $versement['mode']) : 'Mode non précisé' }}
                              @if (! empty($versement['reference']))
                                · Réf : {{ $versement['reference'] }}
                              @endif
                            </p>
                          </div>
                          <span class="font-semibold text-emerald-600 dark:text-emerald-300">
                            {{ number_format($versement['montant'], 0, ',', ' ') }} FCFA
                          </span>
                        </div>
                      @endforeach
                      @foreach ($remises as $remise)
                        <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/40 px-3 py-2">
                          <div>
                            <p class="font-semibold text-slate-700 dark:text-slate-100">Remise</p>
                            <p class="text-slate-600 dark:text-slate-400">
                              {{ $remise['commentaire'] ?: 'Remise appliquée' }}
                            </p>
                          </div>
                          <span class="font-semibold text-amber-600 dark:text-amber-300">
                            {{ $remise['type'] === 'pourcentage' ? $remise['valeur'] . '%' : number_format($remise['valeur'], 0, ',', ' ') . ' FCFA' }}
                          </span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endforeach
              @else
                <p class="text-slate-600 dark:text-slate-400">Historique indisponible.</p>
              @endif
            </div>
          </details>
        </div>
      </div>
    @else
      <div class="rounded-3xl border border-dashed border-slate-200 dark:border-slate-700 p-6 text-center text-sm text-slate-600 dark:text-slate-400">
        Sélectionnez une facture pour afficher le détail, les versements et les remises.
      </div>
    @endif
  </section>

  <x-modal name="facture-versement" maxWidth="2xl">
    <div class="rounded-2xl bg-white dark:bg-slate-950 p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Versements</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee['eleve'] ?? 'Aucun élève sélectionné' }}
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Période : {{ $this->factureSelectionnee['periode'] ?? '—' }}
          </p>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-versement')">✕</button>
      </div>

      @if ($this->factureSelectionnee)
        <div class="mt-6 space-y-4 text-sm text-slate-700 dark:text-slate-200">
          <div class="space-y-3">
            @forelse ($this->factureSelectionnee['versements'] as $versement)
              <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-3">
                <div class="flex items-center justify-between">
                  <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($versement['montant'], 0, ',', ' ') }} FCFA</p>
                  <span class="text-xs text-slate-600 dark:text-slate-400">{{ $versement['date'] }}</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400">{{ ucfirst(str_replace('_', ' ', $versement['mode'])) }} · {{ $versement['reference'] ?? '—' }}</p>
                @if (!empty($versement['commentaire']))
                  <p class="text-xs text-slate-500">{{ $versement['commentaire'] }}</p>
                @endif
              </div>
            @empty
              <p class="text-slate-600 dark:text-slate-400">Aucun versement enregistré.</p>
            @endforelse
          </div>
          <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ajouter un versement</p>
            <div class="grid gap-3 sm:grid-cols-2">
              <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
              <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
            </div>
            <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100">
              <option value="especes">Espèces</option>
              <option value="mobile_money">Mobile money</option>
              <option value="virement">Virement</option>
              <option value="carte">Carte</option>
            </select>
            <details class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2">
              <summary class="cursor-pointer list-none text-xs uppercase tracking-[0.2em] text-slate-500">Champs avancés</summary>
              <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <input wire:model="versementForm.reference" type="text" placeholder="Référence (optionnel)" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
                <input wire:model="versementForm.commentaire" type="text" placeholder="Commentaire" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
              </div>
            </details>
            <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-200 transition hover:bg-emerald-500/30">
              Enregistrer le versement
            </button>
          </div>
        </div>
      @else
        <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">Sélectionnez d'abord une facture.</p>
      @endif
    </div>
  </x-modal>

  <x-modal name="facture-remise" maxWidth="2xl">
    <div class="rounded-2xl bg-white dark:bg-slate-950 p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remises</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee['eleve'] ?? 'Aucun élève sélectionné' }}
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Période : {{ $this->factureSelectionnee['periode'] ?? '—' }}
          </p>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-remise')">✕</button>
      </div>

      @if ($this->factureSelectionnee)
        <div class="mt-6 space-y-4 text-sm text-slate-700 dark:text-slate-200">
          <div class="space-y-3">
            @forelse ($this->factureSelectionnee['remises'] as $remise)
              <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-3">
                <p class="font-semibold text-slate-700 dark:text-slate-100">
                  {{ $remise['type'] === 'pourcentage' ? $remise['valeur'] . '%' : number_format($remise['valeur'], 0, ',', ' ') . ' FCFA' }}
                </p>
                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $remise['commentaire'] ?: 'Remise appliquée automatiquement' }}</p>
              </div>
            @empty
              <p class="text-slate-600 dark:text-slate-400">Aucune remise appliquée pour cette facture.</p>
            @endforelse
          </div>
          <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Appliquer une remise</p>
            <div class="grid gap-3 sm:grid-cols-2">
              <select wire:model="remiseForm.type" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100">
                <option value="pourcentage">Pourcentage</option>
                <option value="montant">Montant fixe</option>
              </select>
              <input wire:model="remiseForm.valeur" type="number" min="0" placeholder="Valeur" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
            </div>
            <input wire:model="remiseForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
            <button wire:click="ajouterRemise" type="button" class="w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-700 dark:text-amber-200 transition hover:bg-amber-500/30">
              Appliquer la remise
            </button>
          </div>
        </div>
      @else
        <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">Sélectionnez d'abord une facture.</p>
      @endif
    </div>
  </x-modal>

  <x-modal name="facture-historique" maxWidth="2xl">
    <div class="rounded-2xl bg-white dark:bg-slate-950 p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Historique</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $this->eleveSelectionne['nom'] ?? 'Aucun élève sélectionné' }}
          </h3>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-historique')">✕</button>
      </div>

      <div class="mt-6 space-y-3 text-sm text-slate-700 dark:text-slate-200">
        @if ($this->eleveSelectionne)
          @foreach ($this->eleveSelectionne['factures'] as $factureEleve)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-3">
              <p class="font-semibold text-slate-900 dark:text-white">{{ $factureEleve['periode'] }}</p>
              <p class="text-xs text-slate-600 dark:text-slate-400">
                Net : {{ number_format($factureEleve['net_a_payer'], 0, ',', ' ') }} FCFA ·
                Versé : {{ number_format($factureEleve['total_verse'], 0, ',', ' ') }} FCFA ·
                Reste : {{ number_format($factureEleve['reste_a_payer'], 0, ',', ' ') }} FCFA
              </p>
              @php
                $versements = $factureEleve['versements'] ?? [];
                $remises = $factureEleve['remises'] ?? [];
                $hasMouvements = count($versements) || count($remises);
              @endphp
              <div class="mt-3 space-y-2 text-xs text-slate-600 dark:text-slate-300">
                @if (! $hasMouvements)
                  <p class="text-slate-500">Aucun mouvement enregistré pour cette période.</p>
                @endif
                @foreach ($versements as $versement)
                  <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/40 px-3 py-2">
                    <div>
                      <p class="font-semibold text-slate-700 dark:text-slate-100">Versement</p>
                      <p class="text-slate-600 dark:text-slate-400">
                        {{ $versement['date'] ?? 'Date inconnue' }} ·
                        {{ $versement['mode'] ? str_replace('_', ' ', $versement['mode']) : 'Mode non précisé' }}
                        @if (! empty($versement['reference']))
                          · Réf : {{ $versement['reference'] }}
                        @endif
                      </p>
                    </div>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-300">
                      {{ number_format($versement['montant'], 0, ',', ' ') }} FCFA
                    </span>
                  </div>
                @endforeach
                @foreach ($remises as $remise)
                  <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/40 px-3 py-2">
                    <div>
                      <p class="font-semibold text-slate-700 dark:text-slate-100">Remise</p>
                      <p class="text-slate-600 dark:text-slate-400">
                        {{ $remise['commentaire'] ?: 'Remise appliquée' }}
                      </p>
                    </div>
                    <span class="font-semibold text-amber-600 dark:text-amber-300">
                      {{ $remise['type'] === 'pourcentage' ? $remise['valeur'] . '%' : number_format($remise['valeur'], 0, ',', ' ') . ' FCFA' }}
                    </span>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        @else
          <p class="text-slate-600 dark:text-slate-400">Historique indisponible.</p>
        @endif
      </div>
    </div>
  </x-modal>
</div>
