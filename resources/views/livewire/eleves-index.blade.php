<div class="space-y-8" data-module="eleves">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Module unifié</p>
            <h1 class="text-2xl font-semibold text-white">Élèves & Classes</h1>
            <p class="mt-1 text-sm text-slate-400">Suivi des inscriptions, statuts cantine et informations parentales.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('eleves.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
                <span class="text-lg">＋</span>
                Nouvel élève
            </a>
            <a href="{{ route('eleves.classes.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-emerald-400 hover:text-emerald-300">
                Gérer les classes
            </a>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 rounded-3xl border border-slate-800 bg-slate-900/60 p-2">
        <a href="{{ route('eleves.index') }}" class="rounded-2xl px-4 py-2 text-sm font-semibold {{ request()->routeIs('eleves.index') ? 'bg-emerald-500/20 text-emerald-200' : 'text-slate-300 hover:text-white' }}">
            Élèves
        </a>
        <a href="{{ route('eleves.classes.index') }}" class="rounded-2xl px-4 py-2 text-sm font-semibold {{ request()->routeIs('eleves.classes.index') ? 'bg-emerald-500/20 text-emerald-200' : 'text-slate-300 hover:text-white' }}">
            Classes
        </a>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total élèves</p>
            <p class="mt-3 text-3xl font-semibold text-white">{{ $eleves->total() }}</p>
            <p class="mt-2 text-sm text-slate-400">Base active de la cantine scolaire.</p>
        </div>
        <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Filtres</p>
            <p class="mt-3 text-sm text-slate-300">Affinez par classe ou statut pour des actions rapides.</p>
            <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-400">
                <span class="rounded-full bg-slate-800 px-3 py-1">{{ $classes->count() }} classes</span>
                <span class="rounded-full bg-slate-800 px-3 py-1">Recherche instantanée</span>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Actions rapides</p>
            <div class="mt-4 flex flex-col gap-2 text-sm text-slate-300">
                <span class="rounded-2xl bg-slate-800/70 px-3 py-2">Changer une classe en 1 clic</span>
                <span class="rounded-2xl bg-slate-800/70 px-3 py-2">Ajouter une note interne</span>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
        <div class="grid gap-4 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Recherche</label>
                <div class="mt-2">
                    <input
                        type="text"
                        wire:model.debounce.400ms="search"
                        placeholder="Nom ou prénom"
                        class="w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none"
                    />
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Classe</label>
                <select wire:model="classeFilter" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none">
                    <option value="">Toutes</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}{{ $classe->niveau ? ' · ' . $classe->niveau : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Statut</label>
                <select wire:model="statutFilter" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none">
                    <option value="">Tous</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-800 bg-slate-900/60">
        <div class="flex flex-col gap-4 border-b border-slate-800 px-6 py-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-white">Liste des élèves</h2>
                <p class="text-sm text-slate-400">Statut administratif et cantine en temps réel.</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                À jour
                <span class="ml-4 inline-flex h-2 w-2 rounded-full bg-amber-300"></span>
                Partiel
                <span class="ml-4 inline-flex h-2 w-2 rounded-full bg-rose-400"></span>
                Retard
            </div>
        </div>

        <div class="hidden lg:block">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr class="border-b border-slate-800">
                        <th class="px-6 py-4">Élève</th>
                        <th class="px-6 py-4">Classe</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4">Cantine</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eleves as $eleve)
                        @php
                            $cantine = $this->cantineStatus($eleve);
                        @endphp
                        <tr wire:key="eleve-row-{{ $eleve->id }}" class="border-b border-slate-800/60 text-slate-200">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-white">{{ $eleve->prenom }} {{ $eleve->nom }}</span>
                                    <span class="text-xs text-slate-500">ID #{{ $eleve->id }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($eleve->classe)
                                    <x-cantine.badge tone="sky">{{ $eleve->classe->nom }}</x-cantine.badge>
                                @else
                                    <x-cantine.badge tone="slate">Non affecté</x-cantine.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-cantine.badge tone="{{ $eleve->statut === 'actif' ? 'emerald' : 'rose' }}">
                                    {{ ucfirst($eleve->statut) }}
                                </x-cantine.badge>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-3 w-3 rounded-full {{ $cantine['dot'] }}"></span>
                                    <x-cantine.badge tone="{{ $cantine['tone'] }}">
                                        {{ $cantine['label'] }}
                                    </x-cantine.badge>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="openDetails({{ $eleve->id }})" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300 transition hover:border-emerald-400 hover:text-emerald-300">
                                        Fiche
                                    </button>
                                    <a href="{{ route('eleves.edit', $eleve) }}" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300 transition hover:border-emerald-400 hover:text-emerald-300">
                                        Modifier
                                    </a>
                                    <button type="button" wire:click="openNoteModal({{ $eleve->id }})" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300 transition hover:border-emerald-400 hover:text-emerald-300">
                                        Note
                                    </button>
                                    <button type="button" wire:click="openClasseModal({{ $eleve->id }}, {{ $eleve->classe_id ?? 'null' }})" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300 transition hover:border-emerald-400 hover:text-emerald-300">
                                        Classe
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">
                                Aucun élève trouvé pour ces filtres.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid gap-4 p-6 lg:hidden">
            @forelse ($eleves as $eleve)
                @php
                    $cantine = $this->cantineStatus($eleve);
                @endphp
                <div wire:key="eleve-card-{{ $eleve->id }}" class="rounded-2xl border border-slate-800 bg-slate-950/50 p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-base font-semibold text-white">{{ $eleve->prenom }} {{ $eleve->nom }}</p>
                            <p class="text-xs text-slate-500">{{ $eleve->classe?->nom ?? 'Non affecté' }}</p>
                        </div>
                        <span class="inline-flex h-3 w-3 rounded-full {{ $cantine['dot'] }}"></span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-cantine.badge tone="{{ $eleve->statut === 'actif' ? 'emerald' : 'rose' }}">
                            {{ ucfirst($eleve->statut) }}
                        </x-cantine.badge>
                        <x-cantine.badge tone="{{ $cantine['tone'] }}">{{ $cantine['label'] }}</x-cantine.badge>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" wire:click="openDetails({{ $eleve->id }})" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300">
                            Fiche
                        </button>
                        <a href="{{ route('eleves.edit', $eleve) }}" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300">
                            Modifier
                        </a>
                        <button type="button" wire:click="openNoteModal({{ $eleve->id }})" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300">
                            Note
                        </button>
                        <button type="button" wire:click="openClasseModal({{ $eleve->id }}, {{ $eleve->classe_id ?? 'null' }})" class="rounded-xl border border-slate-800 px-3 py-2 text-xs text-slate-300">
                            Classe
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">Aucun élève trouvé.</p>
            @endforelse
        </div>

        <div class="border-t border-slate-800 px-6 py-4">
            {{ $eleves->links() }}
        </div>
    </div>

    @if ($detailEleve = $this->detailEleve)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
            <div class="w-full max-w-3xl rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex flex-col gap-4 border-b border-slate-800 pb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Fiche élève</p>
                        <h3 class="text-xl font-semibold text-white">{{ $detailEleve->prenom }} {{ $detailEleve->nom }}</h3>
                        <p class="text-sm text-slate-400">Classe : {{ $detailEleve->classe?->nom ?? 'Non affecté' }}</p>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-2xl border border-slate-800 px-4 py-2 text-sm text-slate-300">
                        Fermer
                    </button>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-white">Informations élève</h4>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/50 p-4 text-sm text-slate-300">
                            <p>Date de naissance : {{ $detailEleve->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}</p>
                            <p>Statut : {{ ucfirst($detailEleve->statut) }}</p>
                            <p>Allergies / régime : {{ $detailEleve->allergies_regime ?? '—' }}</p>
                            <p class="mt-2 text-xs uppercase tracking-[0.2em] text-slate-500">Notes internes</p>
                            <p class="text-sm text-slate-300">{{ $detailEleve->notes_internes ?? 'Aucune note' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-white">Parent / Responsable</h4>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/50 p-4 text-sm text-slate-300">
                            @forelse ($detailEleve->contactsParents as $contact)
                                <p class="font-semibold text-white">{{ $contact->nom }}</p>
                                <p class="text-xs text-slate-500">{{ $contact->lien_parental ?? 'Responsable' }}</p>
                                <p class="mt-2">Tel principal : {{ $contact->telephone_principal ?? '—' }}</p>
                                <p>Tel secondaire : {{ $contact->telephone_secondaire ?? '—' }}</p>
                                <p>Email : {{ $contact->email ?? '—' }}</p>
                            @empty
                                <p>Aucun contact renseigné.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                @php
                    $totalFactures = $detailEleve->factures->sum('montant_total');
                    $totalVerse = $detailEleve->paiements->sum('montant');
                    $resteAPayer = max($totalFactures - $totalVerse, 0);
                @endphp

                <div class="mt-6 space-y-4">
                    <h4 class="text-sm font-semibold text-white">Récapitulatif cantine</h4>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-300">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total facturé</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ number_format($totalFactures, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-300">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total versé</p>
                            <p class="mt-2 text-lg font-semibold text-emerald-200">{{ number_format($totalVerse, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-300">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Reste à payer</p>
                            <p class="mt-2 text-lg font-semibold text-rose-200">{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h4 class="text-sm font-semibold text-white">Versements</h4>
                        <span class="text-xs text-slate-500">Total : {{ number_format($totalVerse, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        @forelse ($detailEleve->paiements->sortByDesc('date_paiement') as $paiement)
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-300">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">
                                            {{ $paiement->mois?->format('m/Y') ?? 'Période' }}
                                        </p>
                                        <p class="mt-1 text-white">
                                            {{ $paiement->mode_paiement ?? 'Mode non précisé' }}
                                            @if ($paiement->reference)
                                                · Ref {{ $paiement->reference }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500">{{ $paiement->date_paiement?->format('d/m/Y') ?? 'Date inconnue' }}</p>
                                        <p class="text-lg font-semibold text-emerald-200">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                </div>
                                @if ($paiement->facture)
                                    <p class="mt-2 text-xs text-slate-500">Facture : {{ $paiement->facture->mois?->format('m/Y') ?? '—' }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Aucun versement enregistré.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-white">Historique des notes</h4>
                    <div class="mt-3 space-y-2">
                        @forelse ($detailEleve->notesEleves as $note)
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-300">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ $note->type_note }}</p>
                                <p class="mt-2 text-white">{{ $note->contenu }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Aucune note enregistrée.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($noteEleveId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
            <div class="w-full max-w-xl rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ajouter une note</p>
                        <h3 class="text-xl font-semibold text-white">Remarque interne</h3>
                    </div>
                    <button type="button" wire:click="closeNoteModal" class="rounded-2xl border border-slate-800 px-4 py-2 text-sm text-slate-300">
                        Fermer
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Type</label>
                        <input
                            type="text"
                            wire:model="noteType"
                            class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none"
                        />
                        @error('noteType') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Contenu</label>
                        <textarea
                            rows="4"
                            wire:model="noteContenu"
                            class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none"
                        ></textarea>
                        @error('noteContenu') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" wire:click="closeNoteModal" class="rounded-2xl border border-slate-800 px-4 py-2 text-sm text-slate-300">
                        Annuler
                    </button>
                    <button type="button" wire:click="saveNote" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($classeChangeEleveId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
            <div class="w-full max-w-xl rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Changer de classe</p>
                        <h3 class="text-xl font-semibold text-white">Affectation</h3>
                    </div>
                    <button type="button" wire:click="closeClasseModal" class="rounded-2xl border border-slate-800 px-4 py-2 text-sm text-slate-300">
                        Fermer
                    </button>
                </div>

                <div class="mt-6">
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nouvelle classe</label>
                    <select wire:model="classeChangeId" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none">
                        <option value="">Non affecté</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}{{ $classe->niveau ? ' · ' . $classe->niveau : '' }}</option>
                        @endforeach
                    </select>
                    @error('classeChangeId') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" wire:click="closeClasseModal" class="rounded-2xl border border-slate-800 px-4 py-2 text-sm text-slate-300">
                        Annuler
                    </button>
                    <button type="button" wire:click="saveClasseChange" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>