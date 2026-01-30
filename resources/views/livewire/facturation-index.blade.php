<div class="space-y-6">
    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6 shadow-lg shadow-slate-950/40">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Vue unifiée</p>
                <h1 class="mt-2 text-2xl font-semibold text-white">Facturation & paiements</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-400">
                    Centralisez les factures, versements et remises dans une seule interface sans rechargement.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button class="rounded-2xl border border-emerald-500/40 bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-200 transition hover:bg-emerald-500/30">
                    Exporter PDF
                </button>
                <button class="rounded-2xl border border-slate-700 bg-slate-800/70 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
                    Exporter CSV
                </button>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Période début</label>
                <input wire:model="periodeDebut" type="month" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Période fin</label>
                <input wire:model="periodeFin" type="month" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Élève</label>
                <input wire:model.debounce.300ms="eleveFilter" type="text" placeholder="Nom ou prénom" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Statut</label>
                <select wire:model="statutFilter" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100">
                    <option value="">Tous les statuts</option>
                    <option value="brouillon">Brouillon</option>
                    <option value="validee">Validée</option>
                    <option value="envoyee">Envoyée</option>
                    <option value="impayee">Impayée</option>
                    <option value="partiellement_payee">Partiellement payée</option>
                    <option value="payee">Payée</option>
                    <option value="non_concernee">Non concernée</option>
                </select>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)]">
        <div class="space-y-4">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Factures</p>
                <h2 class="mt-2 text-lg font-semibold text-white">{{ $this->factures->count() }} facture(s)</h2>
            </div>

            <div class="space-y-3">
                @forelse ($this->factures as $facture)
                    @php
                        $isActive = $this->factureSelectionneeId === $facture['id'];
                        $statutLabels = [
                            'brouillon' => 'Brouillon',
                            'validee' => 'Validée',
                            'envoyee' => 'Envoyée',
                            'impayee' => 'Impayée',
                            'partiellement_payee' => 'Partielle',
                            'payee' => 'Payée',
                            'non_concernee' => 'Non concernée',
                        ];
                        $statutTones = [
                            'brouillon' => 'bg-slate-700 text-slate-200',
                            'validee' => 'bg-sky-500/20 text-sky-200',
                            'envoyee' => 'bg-indigo-500/20 text-indigo-200',
                            'impayee' => 'bg-rose-500/20 text-rose-200',
                            'partiellement_payee' => 'bg-amber-500/20 text-amber-200',
                            'payee' => 'bg-emerald-500/20 text-emerald-200',
                            'non_concernee' => 'bg-slate-700/40 text-slate-300',
                        ];
                    @endphp
                    <button
                        type="button"
                        wire:click="selectionnerFacture({{ $facture['id'] }})"
                        class="w-full rounded-3xl border border-slate-800 p-4 text-left transition hover:border-emerald-400/60 {{ $isActive ? 'bg-emerald-500/10' : 'bg-slate-900/70' }}"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $facture['eleve'] }}</p>
                                <p class="text-xs text-slate-400">{{ $facture['periode'] }}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statutTones[$facture['statut']] ?? 'bg-slate-700 text-slate-200' }}">
                                {{ $statutLabels[$facture['statut']] ?? $facture['statut'] }}
                            </span>
                        </div>
                        <div class="mt-3 grid grid-cols-3 gap-2 text-xs text-slate-400">
                            <div>
                                <p>Net à payer</p>
                                <p class="text-sm font-semibold text-white">{{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div>
                                <p>Versé</p>
                                <p class="text-sm font-semibold text-emerald-200">{{ number_format($facture['total_verse'], 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div>
                                <p>Reste</p>
                                <p class="text-sm font-semibold text-rose-200">{{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-700 p-6 text-center text-sm text-slate-400">
                        Aucune facture ne correspond aux filtres.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Action rapide</p>
                        <h2 class="mt-2 text-lg font-semibold text-white">Choisir un élève</h2>
                        <p class="text-sm text-slate-400">Sélectionnez un élève pour appliquer une remise ou enregistrer un versement.</p>
                    </div>
                    <div class="w-full sm:w-72">
                        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Élève</label>
                        <select wire:model="eleveSelectionneId" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100">
                            <option value="">Sélectionner un élève</option>
                            @foreach ($this->eleves as $eleve)
                                <option value="{{ $eleve['id'] }}">{{ $eleve['nom'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @if ($this->factureSelectionnee)
                @php
                    $facture = $this->factureSelectionnee;
                    $eleveSelectionne = $this->eleveSelectionne;
                @endphp
                @if ($eleveSelectionne)
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Fiche élève</p>
                                <h2 class="mt-2 text-2xl font-semibold text-white">{{ $eleveSelectionne['nom'] }}</h2>
                                <p class="text-sm text-slate-400">Historique des factures et paiements</p>
                            </div>
                            <div class="grid gap-2 text-sm text-slate-200 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Net total</p>
                                    <p class="mt-1 font-semibold">{{ number_format($eleveSelectionne['total_net'], 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Versements</p>
                                    <p class="mt-1 font-semibold text-emerald-200">{{ number_format($eleveSelectionne['total_verse'], 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-3">
                            @foreach ($eleveSelectionne['factures'] as $factureEleve)
                                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-white">{{ $factureEleve['periode'] }}</p>
                                            <p class="text-xs text-slate-400">Net : {{ number_format($factureEleve['net_a_payer'], 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            Versé : <span class="font-semibold text-emerald-200">{{ number_format($factureEleve['total_verse'], 0, ',', ' ') }} FCFA</span>
                                            · Reste : <span class="font-semibold text-rose-200">{{ number_format($factureEleve['reste_a_payer'], 0, ',', ' ') }} FCFA</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Détail facture</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">{{ $facture['eleve'] }}</h2>
                            <p class="text-sm text-slate-400">Période : {{ $facture['periode'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-slate-200">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Statut</p>
                            <p class="mt-1 font-semibold">{{ str_replace('_', ' ', $facture['statut']) }}</p>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Montant brut</p>
                            <p class="mt-1 text-lg font-semibold text-white">{{ number_format($facture['montant_brut'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total remises</p>
                            <p class="mt-1 text-lg font-semibold text-amber-200">{{ number_format($facture['total_remises'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Net à payer</p>
                            <p class="mt-1 text-lg font-semibold text-white">{{ number_format($facture['net_a_payer'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Reste à payer</p>
                            <p class="mt-1 text-lg font-semibold text-rose-200">{{ number_format($facture['reste_a_payer'], 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Remises appliquées</h3>
                            <span class="rounded-full bg-amber-500/20 px-2 py-1 text-xs text-amber-200">
                                {{ count($facture['remises']) }} remise(s)
                            </span>
                        </div>
                        <div class="mt-4 space-y-3 text-sm">
                            @forelse ($facture['remises'] as $remise)
                                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-3">
                                    <p class="font-semibold text-slate-100">
                                        {{ $remise['type'] === 'pourcentage' ? $remise['valeur'] . '%' : number_format($remise['valeur'], 0, ',', ' ') . ' FCFA' }}
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $remise['commentaire'] ?: 'Remise appliquée automatiquement' }}</p>
                                </div>
                            @empty
                                <p class="text-slate-400">Aucune remise appliquée pour cette facture.</p>
                            @endforelse
                        </div>
                        <div class="mt-6 space-y-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ajouter une remise</p>
                            @if ($eleveSelectionne)
                                <select wire:model="factureSelectionneeId" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100">
                                    @foreach ($eleveSelectionne['factures'] as $factureEleve)
                                        <option value="{{ $factureEleve['id'] }}">{{ $factureEleve['periode'] }} · {{ number_format($factureEleve['net_a_payer'], 0, ',', ' ') }} FCFA</option>
                                    @endforeach
                                </select>
                            @endif
                            <div class="grid gap-3 sm:grid-cols-2">
                                <select wire:model="remiseForm.type" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100">
                                    <option value="pourcentage">Pourcentage</option>
                                    <option value="montant">Montant fixe</option>
                                </select>
                                <input wire:model="remiseForm.valeur" type="number" min="0" placeholder="Valeur" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                            </div>
                            <input wire:model="remiseForm.commentaire" type="text" placeholder="Commentaire" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                            <button wire:click="ajouterRemise" type="button" class="w-full rounded-2xl bg-amber-500/20 px-4 py-2 text-sm font-semibold text-amber-200 transition hover:bg-amber-500/30">
                                Appliquer la remise
                            </button>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Versements</h3>
                            <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-xs text-emerald-200">
                                {{ count($facture['versements']) }} versement(s)
                            </span>
                        </div>
                        <div class="mt-4 space-y-3 text-sm">
                            @forelse ($facture['versements'] as $versement)
                                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-3">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-white">{{ number_format($versement['montant'], 0, ',', ' ') }} FCFA</p>
                                        <span class="text-xs text-slate-400">{{ $versement['date'] }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $versement['mode'])) }} · {{ $versement['reference'] ?? '—' }}</p>
                                    @if (!empty($versement['commentaire']))
                                        <p class="text-xs text-slate-500">{{ $versement['commentaire'] }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-slate-400">Aucun versement enregistré.</p>
                            @endforelse
                        </div>
                        <div class="mt-6 space-y-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ajouter un versement</p>
                            @if ($eleveSelectionne)
                                <select wire:model="factureSelectionneeId" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100">
                                    @foreach ($eleveSelectionne['factures'] as $factureEleve)
                                        <option value="{{ $factureEleve['id'] }}">{{ $factureEleve['periode'] }} · {{ number_format($factureEleve['net_a_payer'], 0, ',', ' ') }} FCFA</option>
                                    @endforeach
                                </select>
                            @endif
                            <div class="grid gap-3 sm:grid-cols-2">
                                <input wire:model="versementForm.montant" type="number" min="0" placeholder="Montant" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                                <input wire:model="versementForm.date" type="date" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                            </div>
                            <select wire:model="versementForm.mode" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100">
                                <option value="especes">Espèces</option>
                                <option value="mobile_money">Mobile money</option>
                                <option value="virement">Virement</option>
                                <option value="carte">Carte</option>
                            </select>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <input wire:model="versementForm.reference" type="text" placeholder="Référence (optionnel)" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                                <input wire:model="versementForm.commentaire" type="text" placeholder="Commentaire" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                            </div>
                            <button wire:click="ajouterVersement" type="button" class="w-full rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-200 transition hover:bg-emerald-500/30">
                                Enregistrer le versement
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-slate-700 p-6 text-center text-sm text-slate-400">
                    Sélectionnez une facture pour afficher le détail, les versements et les remises.
                </div>
            @endif
        </div>
    </section>
</div>
