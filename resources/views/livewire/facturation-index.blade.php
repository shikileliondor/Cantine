<div class="space-y-6" x-data>
  @php
    $factures = $this->facturesPaginated;
  @endphp

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Vue simple</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Facturation</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
          Liste rapide des élèves avec actions directes : versement, historique, modification et note interne.
        </p>
      </div>
      <div class="text-right text-xs text-slate-500">
        {{ $factures->total() }} élève(s)
      </div>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr_auto] lg:items-end">
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Nom élève</label>
        <input
          wire:model.defer="nomRecherche"
          type="text"
          placeholder="Nom ou prénom"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        />
      </div>
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Classe</label>
        <select
          wire:model.defer="classeRecherche"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        >
          <option value="">Toutes les classes</option>
          @foreach ($this->classes as $classe)
            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
          @endforeach
        </select>
      </div>
      <button
        type="button"
        wire:click="appliquerFiltres"
        class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200"
      >
        Rechercher
      </button>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
      <div class="hidden grid-cols-[1.4fr_1fr_0.9fr_1fr_1.4fr] gap-4 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:border-slate-800 dark:bg-slate-950 lg:grid">
        <span>Élève</span>
        <span>Classe</span>
        <span>Statut</span>
        <span>Reste à payer</span>
        <span class="text-right">Actions</span>
      </div>

      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        @forelse ($factures as $facture)
          @php
            $badge = $this->statutBadge($facture['statut']);
            $hasFacture = ! empty($facture['id']);
          @endphp
          <div class="grid gap-4 px-4 py-4 lg:grid-cols-[1.4fr_1fr_0.9fr_1fr_1.4fr] lg:items-center">
            <div>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</p>
              <p class="text-xs text-slate-500">Dernière période : {{ $facture['periode'] }}</p>
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-300">
              {{ $facture['classe'] ?? '—' }}
            </div>
            <div>
              <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badge['classes'] }}">
                <span class="h-2 w-2 rounded-full {{ $badge['dot'] }}"></span>
                {{ $badge['label'] }}
              </span>
            </div>
            <div class="text-sm font-semibold text-slate-900 dark:text-white">
              {{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA
            </div>
            <div class="flex flex-wrap justify-start gap-2 text-xs font-semibold lg:justify-end">
              @if ($hasFacture)
                <button
                  type="button"
                  x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-versement'))"
                  class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-emerald-700 transition hover:bg-emerald-500/20 dark:text-emerald-100"
                >
                  Ajouter un versement
                </button>
                <button
                  type="button"
                  x-on:click="$wire.selectionnerFacture({{ $facture['id'] }}).then(() => $dispatch('open-modal', 'facture-historique'))"
                  class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                  Historique
                </button>
              @else
                <span class="rounded-xl border border-emerald-500/30 bg-emerald-500/5 px-3 py-2 text-emerald-400">
                  Ajouter un versement
                </span>
                <span class="rounded-xl border border-slate-200 px-3 py-2 text-slate-400 dark:border-slate-700">
                  Historique
                </span>
              @endif
              <a
                href="{{ route('eleves.edit', $facture['eleve_id']) }}"
                class="rounded-xl border border-slate-200 px-3 py-2 text-slate-600 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:text-slate-300"
              >
                Modifier
              </a>
              <button
                type="button"
                x-on:click="$wire.openNoteModal({{ $facture['eleve_id'] }}); $dispatch('open-modal', 'facture-note')"
                class="rounded-xl border border-slate-200 px-3 py-2 text-slate-600 transition hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-700 dark:text-slate-300 dark:hover:text-emerald-300"
              >
                Note
              </button>
            </div>
          </div>
        @empty
          <div class="px-4 py-6 text-center text-sm text-slate-600 dark:text-slate-400">
            Aucun élève ne correspond aux filtres.
          </div>
        @endforelse
      </div>
    </div>

    <div class="mt-6">
      {{ $factures->links() }}
    </div>
  </section>

  <x-modal name="facture-versement" maxWidth="2xl">
    <div class="rounded-2xl bg-white p-6 dark:bg-slate-950">
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
              <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
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
              <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
              <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
            </div>
            <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
              <option value="especes">Espèces</option>
              <option value="mobile_money">Mobile money</option>
              <option value="virement">Virement</option>
              <option value="carte">Carte</option>
            </select>
            <input wire:model="versementForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
            <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200">
              Enregistrer le versement
            </button>
          </div>
        </div>
      @else
        <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">Sélectionnez d'abord une facture.</p>
      @endif
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
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
              <p class="font-semibold text-slate-900 dark:text-white">{{ $factureEleve['periode'] }}</p>
              <p class="text-xs text-slate-600 dark:text-slate-400">
                Reste : {{ number_format($factureEleve['reste_a_payer'], 0, ',', ' ') }} FCFA
              </p>
              @php
                $versements = $factureEleve['versements'] ?? [];
              @endphp
              <div class="mt-3 space-y-2 text-xs text-slate-600 dark:text-slate-300">
                @if (! count($versements))
                  <p class="text-slate-500">Aucun versement enregistré.</p>
                @endif
                @foreach ($versements as $versement)
                  <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 bg-slate-50/70 px-3 py-2 dark:border-slate-800 dark:bg-slate-950/40">
                    <div>
                      <p class="font-semibold text-slate-700 dark:text-slate-100">Versement</p>
                      <p class="text-slate-600 dark:text-slate-400">
                        {{ $versement['date'] ?? 'Date inconnue' }} ·
                        {{ $versement['mode'] ? str_replace('_', ' ', $versement['mode']) : 'Mode non précisé' }}
                      </p>
                    </div>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-300">
                      {{ number_format($versement['montant'], 0, ',', ' ') }} FCFA
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

  <x-modal name="facture-note" maxWidth="xl">
    <div class="rounded-2xl bg-white p-6 dark:bg-slate-950">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Note interne</p>
          <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Ajouter une note</h3>
        </div>
        <button type="button" class="text-slate-500 hover:text-slate-700" x-on:click="$dispatch('close-modal', 'facture-note')">✕</button>
      </div>

      <div class="mt-6 space-y-4 text-sm text-slate-700 dark:text-slate-200">
        <div>
          <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Type</label>
          <select wire:model="noteType" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
            <option value="interne">Interne</option>
            <option value="cantine">Cantine</option>
            <option value="paiement">Paiement</option>
          </select>
        </div>
        <div>
          <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Contenu</label>
          <textarea wire:model="noteContenu" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"></textarea>
        </div>
        <div class="flex justify-end gap-3">
          <button type="button" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-600 dark:border-slate-700 dark:text-slate-300" x-on:click="$dispatch('close-modal', 'facture-note')">
            Annuler
          </button>
          <button type="button" wire:click="saveNote" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white">
            Enregistrer
          </button>
        </div>
      </div>
    </div>
  </x-modal>
</div>
