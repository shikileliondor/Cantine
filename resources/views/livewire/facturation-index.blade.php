<div class="space-y-6" x-data>
  @php
    $factures = $this->facturesPaginated;
  @endphp

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Module simple</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Facturation</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
          Gérez chaque facture depuis la liste : versement, remise, historique et aperçu.
        </p>
      </div>
      <div class="text-right text-xs text-slate-500">
        {{ $factures->total() }} facture(s)
      </div>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto] lg:items-end">
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Période</label>
        <select
          wire:model.defer="periodeFilter"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        >
          <option value="">Toutes les périodes</option>
          @foreach ($this->moisDisponibles as $periode)
            <option value="{{ $periode }}">{{ $periode }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Élève</label>
        <input
          wire:model.defer="nomRecherche"
          type="text"
          placeholder="Nom ou prénom"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        />
      </div>
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Statut</label>
        <select
          wire:model.defer="statutFilter"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        >
          <option value="">Tous les statuts</option>
          <option value="payee">Soldé</option>
          <option value="partiellement_payee">Partiel</option>
          <option value="impayee">Impayé</option>
          <option value="non_concernee">Non concerné</option>
        </select>
      </div>
      <button
        type="button"
        wire:click="appliquerFiltres"
        class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200"
      >
        Appliquer
      </button>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="space-y-4 lg:hidden">
      @forelse ($factures as $facture)
        @php
          $badge = $this->statutBadge($facture['statut']);
        @endphp
        <article class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950/60">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</p>
              <p class="text-xs text-slate-500">{{ $facture['periode'] }}</p>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badge['classes'] }}">
              <span class="h-2 w-2 rounded-full {{ $badge['dot'] }}"></span>
              {{ $badge['label'] }}
            </span>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
            <div>
              <p class="text-slate-500">Net à payer</p>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div>
              <p class="text-slate-500">Reste à payer</p>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA</p>
            </div>
          </div>
          <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold">
            <button
              type="button"
              x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-versement'))"
              class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-emerald-700 transition hover:bg-emerald-500/20 dark:text-emerald-100"
            >
              ➕ Versement
            </button>
            <button
              type="button"
              x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-remise'))"
              class="rounded-xl border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-amber-700 transition hover:bg-amber-500/20 dark:text-amber-100"
            >
              💸 Remise
            </button>
            <button
              type="button"
              x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-historique'))"
              class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            >
              📜 Historique
            </button>
            <button
              type="button"
              x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-detail'))"
              class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            >
              👁️ Voir
            </button>
          </div>
        </article>
      @empty
        <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-600 dark:border-slate-700 dark:text-slate-400">
          Aucune facture ne correspond aux filtres.
        </div>
      @endforelse
    </div>

    <div class="hidden overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 lg:block">
      <div class="grid grid-cols-[1.4fr_1fr_1fr_1fr_0.9fr_1.5fr] gap-4 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:border-slate-800 dark:bg-slate-950">
        <span>Élève</span>
        <span>Période</span>
        <span>Net à payer</span>
        <span>Reste à payer</span>
        <span>Statut</span>
        <span class="text-right">Actions</span>
      </div>

      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        @forelse ($factures as $facture)
          @php
            $badge = $this->statutBadge($facture['statut']);
          @endphp
          <div class="grid grid-cols-[1.4fr_1fr_1fr_1fr_0.9fr_1.5fr] items-center gap-4 px-4 py-4">
            <div>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</p>
              <p class="text-xs text-slate-500">{{ $facture['classe'] ?? '—' }}</p>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $facture['periode'] }}</p>
            <p class="text-sm font-semibold text-slate-900 dark:text-white">
              {{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA
            </p>
            <p class="text-sm font-semibold text-slate-900 dark:text-white">
              {{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA
            </p>
            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badge['classes'] }}">
              <span class="h-2 w-2 rounded-full {{ $badge['dot'] }}"></span>
              {{ $badge['label'] }}
            </span>
            <div class="flex flex-wrap justify-end gap-2 text-xs font-semibold">
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-versement'))"
                class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-emerald-700 transition hover:bg-emerald-500/20 dark:text-emerald-100"
              >
                ➕ Versement
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-remise'))"
                class="rounded-xl border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-amber-700 transition hover:bg-amber-500/20 dark:text-amber-100"
              >
                💸 Remise
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-historique'))"
                class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                📜 Historique
              </button>
              <button
                type="button"
                x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-detail'))"
                class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                👁️ Voir
              </button>
            </div>
          </div>
        @empty
          <div class="px-4 py-6 text-center text-sm text-slate-600 dark:text-slate-400">
            Aucune facture ne correspond aux filtres.
          </div>
        @endforelse
      </div>
    </div>

    <div class="mt-6">
      {{ $factures->links() }}
    </div>
  </section>

  <x-modal name="facture-versement" maxWidth="xl">
    <div class="rounded-2xl bg-white p-6 dark:bg-slate-950">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Versement</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee['eleve'] ?? 'Aucun élève sélectionné' }}
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Période : {{ $this->factureSelectionnee['periode'] ?? '—' }} · Reste :
            {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['reste_a_payer'], 0, ',', ' ') : '0' }} FCFA
          </p>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-versement')">✕</button>
      </div>

      <div class="mt-6 space-y-4 text-sm text-slate-700 dark:text-slate-200">
        <div class="grid gap-3 sm:grid-cols-2">
          <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
          <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
        </div>
        <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
          <option value="especes">Espèces</option>
          <option value="mobile_money">Mobile money</option>
          <option value="virement">Virement</option>
          <option value="carte">Carte</option>
        </select>
        <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200">
          Enregistrer
        </button>
      </div>
    </div>
  </x-modal>

  <x-modal name="facture-remise" maxWidth="xl">
    <div class="rounded-2xl bg-white p-6 dark:bg-slate-950">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remise</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee['eleve'] ?? 'Aucun élève sélectionné' }}
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Montant brut : {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['montant_brut'], 0, ',', ' ') : '0' }} FCFA
          </p>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-remise')">✕</button>
      </div>

      <div class="mt-6 space-y-4 text-sm text-slate-700 dark:text-slate-200">
        <div class="grid gap-3 sm:grid-cols-2">
          <select wire:model="remiseForm.type" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
            <option value="pourcentage">Pourcentage</option>
            <option value="montant">Montant</option>
          </select>
          <input wire:model="remiseForm.valeur" type="number" min="0" placeholder="Valeur" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
        </div>
        <input wire:model="remiseForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
        <button wire:click="ajouterRemise" type="button" class="w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-500/30 dark:text-amber-200">
          Appliquer
        </button>
      </div>
    </div>
  </x-modal>

  <x-modal name="facture-historique" maxWidth="2xl">
    <div class="rounded-2xl bg-white p-6 dark:bg-slate-950">
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
            @php
              $badge = $this->statutBadge($factureEleve['statut']);
            @endphp
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
              <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                  <p class="font-semibold text-slate-900 dark:text-white">{{ $factureEleve['periode'] }}</p>
                  <p class="text-xs text-slate-600 dark:text-slate-400">
                    Net : {{ number_format($factureEleve['net_a_payer'], 0, ',', ' ') }} FCFA · Reste :
                    {{ number_format($factureEleve['reste_a_payer'], 0, ',', ' ') }} FCFA
                  </p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badge['classes'] }}">
                  <span class="h-2 w-2 rounded-full {{ $badge['dot'] }}"></span>
                  {{ $badge['label'] }}
                </span>
              </div>
            </div>
          @endforeach
        @else
          <p class="text-slate-600 dark:text-slate-400">Historique indisponible.</p>
        @endif
      </div>
    </div>
  </x-modal>

  <x-modal name="facture-detail" maxWidth="xl">
    <div class="rounded-2xl bg-white p-6 dark:bg-slate-950">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Détail facture</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee['eleve'] ?? 'Aucun élève sélectionné' }}
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Période : {{ $this->factureSelectionnee['periode'] ?? '—' }}
          </p>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-detail')">✕</button>
      </div>

      <div class="mt-6 grid gap-3 text-sm text-slate-700 dark:text-slate-200">
        <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-800">
          <span>Montant brut</span>
          <span class="font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['montant_brut'], 0, ',', ' ') : '0' }} FCFA
          </span>
        </div>
        <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-800">
          <span>Remises</span>
          <span class="font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['total_remises'], 0, ',', ' ') : '0' }} FCFA
          </span>
        </div>
        <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-800">
          <span>Net à payer</span>
          <span class="font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['net_a_payer'], 0, ',', ' ') : '0' }} FCFA
          </span>
        </div>
        <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-800">
          <span>Total versé</span>
          <span class="font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['total_verse'], 0, ',', ' ') : '0' }} FCFA
          </span>
        </div>
        <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-800">
          <span>Reste à payer</span>
          <span class="font-semibold text-slate-900 dark:text-white">
            {{ $this->factureSelectionnee ? number_format($this->factureSelectionnee['reste_a_payer'], 0, ',', ' ') : '0' }} FCFA
          </span>
        </div>
      </div>
    </div>
  </x-modal>
</div>
