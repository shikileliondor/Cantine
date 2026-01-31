<div class="space-y-6">
  @php
    $factures = $this->facturesPaginated;
  @endphp

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-lg shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Factures</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Facturation</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
          Liste simple des factures avec actions rapides : versement, remise, historique et vue détaillée.
        </p>
      </div>
      <div class="text-right text-xs text-slate-500">
        {{ $factures->total() }} facture(s)
      </div>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Élève</label>
        <select
          wire:model.defer="eleveFilter"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        >
          <option value="">Tous les élèves</option>
          @foreach ($this->eleves as $eleve)
            <option value="{{ $eleve['id'] }}">{{ $eleve['nom'] }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Statut</label>
        <select
          wire:model.defer="statutFilter"
          class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        >
          <option value="">Tous les statuts</option>
          <option value="a_jour">Soldée</option>
          <option value="partiel">Partiel</option>
          <option value="retard">Impayée</option>
        </select>
      </div>
      <button
        type="button"
        wire:click="appliquerFiltres"
        class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200"
      >
        Filtrer
      </button>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
    <div class="hidden overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 lg:block">
      <div class="grid grid-cols-[1.6fr_0.8fr_1fr_1fr_0.9fr_1.6fr] gap-4 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:border-slate-800 dark:bg-slate-950">
        <span>Élève</span>
        <span>Période</span>
        <span>Net à payer</span>
        <span>Reste</span>
        <span>Statut</span>
        <span class="text-right">Actions</span>
      </div>

      <div class="divide-y divide-slate-200 dark:divide-slate-800">
        @forelse ($factures as $facture)
          @php
            $badge = $this->statutBadge($facture['statut']);
          @endphp
          <div
            class="grid cursor-pointer grid-cols-[1.6fr_0.8fr_1fr_1fr_0.9fr_1.6fr] items-center gap-4 px-4 py-4"
            wire:click="selectionnerFacture({{ $facture['id'] }})"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</p>
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-300">
              {{ $facture['periode'] }}
            </div>
            <div class="text-sm font-semibold text-slate-900 dark:text-white">
              {{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA
            </div>
            <div class="text-sm font-semibold text-slate-900 dark:text-white">
              {{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA
            </div>
            <div>
              <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badge['classes'] }}">
                <span class="h-2 w-2 rounded-full {{ $badge['dot'] }}"></span>
                {{ $badge['label'] }}
              </span>
            </div>
            <div class="flex flex-wrap justify-end gap-2 text-xs font-semibold">
              <button
                type="button"
                wire:click.stop="selectionnerFacture({{ $facture['id'] }}, 'versement')"
                class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-emerald-700 transition hover:bg-emerald-500/20 dark:text-emerald-100"
              >
                ➕ Versement
              </button>
              <button
                type="button"
                wire:click.stop="selectionnerFacture({{ $facture['id'] }}, 'remise')"
                class="rounded-xl border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-amber-700 transition hover:bg-amber-500/20 dark:text-amber-100"
              >
                💸 Remise
              </button>
              <button
                type="button"
                wire:click.stop="selectionnerFacture({{ $facture['id'] }}, 'historique')"
                class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                📜 Historique
              </button>
            </div>
          </div>
          @if ($factureSelectionneeId === $facture['id'])
            <div class="border-t border-slate-200 bg-slate-50/70 px-4 py-5 dark:border-slate-800 dark:bg-slate-950/40">
              <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <button
                  type="button"
                  wire:click="changerSection('versement')"
                  class="{{ $sectionActive === 'versement' ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-200' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300' }} rounded-xl border border-slate-200 px-3 py-2 transition hover:bg-emerald-500/10 dark:border-slate-700"
                >
                  Versement
                </button>
                <button
                  type="button"
                  wire:click="changerSection('remise')"
                  class="{{ $sectionActive === 'remise' ? 'bg-amber-500/20 text-amber-700 dark:text-amber-200' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300' }} rounded-xl border border-slate-200 px-3 py-2 transition hover:bg-amber-500/10 dark:border-slate-700"
                >
                  Remise
                </button>
                <button
                  type="button"
                  wire:click="changerSection('historique')"
                  class="{{ $sectionActive === 'historique' ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-200' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300' }} rounded-xl border border-slate-200 px-3 py-2 transition hover:bg-slate-100 dark:border-slate-700"
                >
                  Historique
                </button>
              </div>

              @if ($sectionActive === 'versement')
                <div class="mt-4 grid gap-4 text-sm text-slate-700 dark:text-slate-200">
                  <div class="grid gap-3 sm:grid-cols-2">
                    <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                    <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                  </div>
                  <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
                    <option value="especes">Espèces</option>
                    <option value="cheque">Chèque</option>
                    <option value="virement">Virement</option>
                    <option value="carte">Carte</option>
                    <option value="autre">Autre</option>
                  </select>
                  <div class="grid gap-3 sm:grid-cols-2">
                    <input wire:model="versementForm.reference" type="text" placeholder="Référence (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                    <input wire:model="versementForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                  </div>
                  <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200">
                    Enregistrer le versement
                  </button>
                </div>
              @elseif ($sectionActive === 'remise')
                <div class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-200">
                  <select wire:model="remiseForm.type" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
                    <option value="fixe">Montant fixe</option>
                    <option value="pourcentage">Pourcentage</option>
                  </select>
                  <div class="grid gap-3 sm:grid-cols-2">
                    <input wire:model="remiseForm.valeur" type="number" min="0" placeholder="Valeur" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                    <input wire:model="remiseForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                  </div>
                  <button wire:click="ajouterRemise" type="button" class="w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-500/30 dark:text-amber-200">
                    Appliquer la remise
                  </button>
                </div>
              @elseif ($sectionActive === 'historique')
                <div class="mt-4 grid gap-6 text-sm text-slate-700 dark:text-slate-200">
                  <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Versements</p>
                    <div class="mt-3 space-y-2">
                      @forelse ($this->factureSelectionnee['versements'] as $versement)
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
                          <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($versement['montant'], 0, ',', ' ') }} FCFA</p>
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $versement['date'] }}</span>
                          </div>
                          <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ ucfirst(str_replace('_', ' ', $versement['mode'])) }} · {{ $versement['reference'] ?? '—' }}
                          </p>
                          @if (!empty($versement['commentaire']))
                            <p class="text-xs text-slate-500">{{ $versement['commentaire'] }}</p>
                          @endif
                        </div>
                      @empty
                        <p class="text-slate-500">Aucun versement enregistré.</p>
                      @endforelse
                    </div>
                  </div>

                  <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remises</p>
                    <div class="mt-3 space-y-2">
                      @forelse ($this->factureSelectionnee['remises'] as $remise)
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
                          <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-900 dark:text-white">
                              {{ $remise['type'] === 'pourcentage' ? $remise['valeur'].'%' : number_format($remise['valeur'], 0, ',', ' ').' FCFA' }}
                            </p>
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $remise['date'] ?? '—' }}</span>
                          </div>
                          <p class="text-xs text-slate-600 dark:text-slate-400">{{ $remise['commentaire'] ?? 'Remise' }}</p>
                        </div>
                      @empty
                        <p class="text-slate-500">Aucune remise enregistrée.</p>
                      @endforelse
                    </div>
                  </div>
                </div>
              @endif
            </div>
          @endif
        @empty
          <div class="px-4 py-6 text-center text-sm text-slate-600 dark:text-slate-400">
            Aucune facture ne correspond aux filtres.
          </div>
        @endforelse
      </div>
    </div>

    <div class="space-y-4 lg:hidden">
      @forelse ($factures as $facture)
        @php
          $badge = $this->statutBadge($facture['statut']);
        @endphp
        <div
          class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/60"
          wire:click="selectionnerFacture({{ $facture['id'] }})"
        >
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $facture['eleve'] }}</p>
              <p class="text-xs text-slate-500">Période : {{ $facture['periode'] }}</p>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badge['classes'] }}">
              <span class="h-2 w-2 rounded-full {{ $badge['dot'] }}"></span>
              {{ $badge['label'] }}
            </span>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div>
              <p class="text-xs text-slate-500">Net à payer</p>
              <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div>
              <p class="text-xs text-slate-500">Reste</p>
              <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA</p>
            </div>
          </div>
          <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold">
            <button
              type="button"
              wire:click.stop="selectionnerFacture({{ $facture['id'] }}, 'versement')"
              class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-emerald-700"
            >
              ➕ Versement
            </button>
            <button
              type="button"
              wire:click.stop="selectionnerFacture({{ $facture['id'] }}, 'remise')"
              class="rounded-xl border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-amber-700"
            >
              💸 Remise
            </button>
            <button
              type="button"
              wire:click.stop="selectionnerFacture({{ $facture['id'] }}, 'historique')"
              class="rounded-xl border border-slate-200 px-3 py-2 text-slate-700 dark:border-slate-700 dark:text-slate-200"
            >
              📜 Historique
            </button>
          </div>
          @if ($factureSelectionneeId === $facture['id'])
            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
              <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <button
                  type="button"
                  wire:click="changerSection('versement')"
                  class="{{ $sectionActive === 'versement' ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-200' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300' }} rounded-xl border border-slate-200 px-3 py-2 dark:border-slate-700"
                >
                  Versement
                </button>
                <button
                  type="button"
                  wire:click="changerSection('remise')"
                  class="{{ $sectionActive === 'remise' ? 'bg-amber-500/20 text-amber-700 dark:text-amber-200' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300' }} rounded-xl border border-slate-200 px-3 py-2 dark:border-slate-700"
                >
                  Remise
                </button>
                <button
                  type="button"
                  wire:click="changerSection('historique')"
                  class="{{ $sectionActive === 'historique' ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-200' : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300' }} rounded-xl border border-slate-200 px-3 py-2 dark:border-slate-700"
                >
                  Historique
                </button>
              </div>

              @if ($sectionActive === 'versement')
                <div class="mt-4 grid gap-4 text-sm text-slate-700 dark:text-slate-200">
                  <div class="grid gap-3 sm:grid-cols-2">
                    <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                    <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                  </div>
                  <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
                    <option value="especes">Espèces</option>
                    <option value="cheque">Chèque</option>
                    <option value="virement">Virement</option>
                    <option value="carte">Carte</option>
                    <option value="autre">Autre</option>
                  </select>
                  <div class="grid gap-3 sm:grid-cols-2">
                    <input wire:model="versementForm.reference" type="text" placeholder="Référence (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                    <input wire:model="versementForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                  </div>
                  <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200">
                    Enregistrer le versement
                  </button>
                </div>
              @elseif ($sectionActive === 'remise')
                <div class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-200">
                  <select wire:model="remiseForm.type" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
                    <option value="fixe">Montant fixe</option>
                    <option value="pourcentage">Pourcentage</option>
                  </select>
                  <div class="grid gap-3 sm:grid-cols-2">
                    <input wire:model="remiseForm.valeur" type="number" min="0" placeholder="Valeur" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                    <input wire:model="remiseForm.commentaire" type="text" placeholder="Commentaire (optionnel)" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100" />
                  </div>
                  <button wire:click="ajouterRemise" type="button" class="w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-500/30 dark:text-amber-200">
                    Appliquer la remise
                  </button>
                </div>
              @elseif ($sectionActive === 'historique')
                <div class="mt-4 grid gap-6 text-sm text-slate-700 dark:text-slate-200">
                  <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Versements</p>
                    <div class="mt-3 space-y-2">
                      @forelse ($this->factureSelectionnee['versements'] as $versement)
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
                          <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($versement['montant'], 0, ',', ' ') }} FCFA</p>
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $versement['date'] }}</span>
                          </div>
                          <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ ucfirst(str_replace('_', ' ', $versement['mode'])) }} · {{ $versement['reference'] ?? '—' }}
                          </p>
                          @if (!empty($versement['commentaire']))
                            <p class="text-xs text-slate-500">{{ $versement['commentaire'] }}</p>
                          @endif
                        </div>
                      @empty
                        <p class="text-slate-500">Aucun versement enregistré.</p>
                      @endforelse
                    </div>
                  </div>

                  <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Remises</p>
                    <div class="mt-3 space-y-2">
                      @forelse ($this->factureSelectionnee['remises'] as $remise)
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3 dark:border-slate-800 dark:bg-slate-900/60">
                          <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-900 dark:text-white">
                              {{ $remise['type'] === 'pourcentage' ? $remise['valeur'].'%' : number_format($remise['valeur'], 0, ',', ' ').' FCFA' }}
                            </p>
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $remise['date'] ?? '—' }}</span>
                          </div>
                          <p class="text-xs text-slate-600 dark:text-slate-400">{{ $remise['commentaire'] ?? 'Remise' }}</p>
                        </div>
                      @empty
                        <p class="text-slate-500">Aucune remise enregistrée.</p>
                      @endforelse
                    </div>
                  </div>
                </div>
              @endif
            </div>
          @endif
        </div>
      @empty
        <div class="px-4 py-6 text-center text-sm text-slate-600 dark:text-slate-400">
          Aucune facture ne correspond aux filtres.
        </div>
      @endforelse
    </div>

    <div class="mt-6">
      {{ $factures->links() }}
    </div>
  </section>

</div>
