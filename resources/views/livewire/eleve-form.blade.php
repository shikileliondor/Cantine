<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Élèves & Classes</p>
            <h1 class="text-2xl font-semibold text-white">{{ $eleve ? 'Modifier un élève' : 'Nouvel élève' }}</h1>
            <p class="mt-1 text-sm text-slate-400">Formulaire complet avec informations parentales et allergies.</p>
        </div>
        <a href="{{ route('eleves.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-emerald-400 hover:text-emerald-300">
            Retour à la liste
        </a>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                    <h2 class="text-lg font-semibold text-white">Informations élève</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Prénom</label>
                            <input type="text" wire:model="prenom" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('prenom') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nom</label>
                            <input type="text" wire:model="nom" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('nom') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Date de naissance</label>
                            <input type="date" wire:model="date_naissance" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('date_naissance') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Statut</label>
                            <select wire:model="statut" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none">
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                            </select>
                            @error('statut') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Classe</label>
                            <select wire:model="classe_id" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none">
                                <option value="">Non affecté</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}{{ $classe->niveau ? ' · ' . $classe->niveau : '' }}</option>
                                @endforeach
                            </select>
                            @error('classe_id') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                    <h2 class="text-lg font-semibold text-white">Notes & allergies</h2>
                    <div class="mt-6 grid gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Notes internes</label>
                            <textarea rows="4" wire:model="notes_internes" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none"></textarea>
                            @error('notes_internes') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Allergies / régime spécial</label>
                            <input type="text" wire:model="allergies_regime" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('allergies_regime') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                    <h2 class="text-lg font-semibold text-white">Parent / Responsable</h2>
                    <div class="mt-6 space-y-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nom</label>
                            <input type="text" wire:model="parent_nom" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('parent_nom') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Lien parental</label>
                            <input type="text" wire:model="parent_lien_parental" placeholder="Père, mère, tuteur..." class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('parent_lien_parental') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Téléphone principal</label>
                            <input type="text" wire:model="parent_telephone_principal" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('parent_telephone_principal') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Téléphone secondaire</label>
                            <input type="text" wire:model="parent_telephone_secondaire" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('parent_telephone_secondaire') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email</label>
                            <input type="email" wire:model="parent_email" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 focus:border-emerald-400 focus:outline-none" />
                            @error('parent_email') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900/80 to-slate-950 p-6">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Conseil</p>
                    <p class="mt-3 text-sm text-slate-300">Ajoutez une note interne pour partager une information utile avec l'équipe cantine.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('eleves.index') }}" class="rounded-2xl border border-slate-800 px-4 py-2 text-sm font-semibold text-slate-200">
                Annuler
            </a>
            <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950">
                Enregistrer
            </button>
        </div>
    </form>
</div>
