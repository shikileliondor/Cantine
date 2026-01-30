<div class="space-y-6">
  <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6 shadow-lg shadow-slate-950/40">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Configuration</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Paramètres généraux</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
          Définissez l'année scolaire active, les mois de facturation et clôturez les périodes terminées.
        </p>
      </div>
      @if (session('status'))
        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-700 dark:text-emerald-200">
          {{ session('status') }}
        </div>
      @endif
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ajouter une année scolaire</h2>
    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Indiquez l'année en cours et la liste des mois utilisés dans les modules.</p>
    <div class="mt-6 grid gap-4 md:grid-cols-2">
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Année début</label>
        <input wire:model="annee_debut" type="number" placeholder="2024" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
        @error('annee_debut') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Année fin</label>
        <input wire:model="annee_fin" type="number" placeholder="2025" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100" />
        @error('annee_fin') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
      </div>
    </div>
    <div class="mt-4">
      <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Mois (un par ligne)</label>
      <textarea wire:model="mois_personnalises" rows="4" placeholder="Septembre\nOctobre\nNovembre" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm text-slate-700 dark:text-slate-100"></textarea>
      @error('mois_personnalises') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
    </div>
    <div class="mt-4 flex items-center gap-3">
      <input id="activer" wire:model="activer" type="checkbox" class="h-4 w-4 rounded border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-emerald-500" />
      <label for="activer" class="text-sm text-slate-600 dark:text-slate-300">Définir comme année active</label>
    </div>
    <div class="mt-6">
      <button wire:click="save" type="button" class="rounded-2xl bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-200 transition hover:bg-emerald-500/30">
        Enregistrer l'année scolaire
      </button>
    </div>
  </section>

  <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Années scolaires</h2>
    <div class="mt-6 space-y-4">
      @forelse ($annees as $annee)
        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 p-5">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
              <p class="text-sm text-slate-600 dark:text-slate-400">Année scolaire</p>
              <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $annee->libelle }}</p>
              <p class="mt-2 text-xs text-slate-500">
                Mois : {{ $annee->mois_personnalises ? implode(', ', $annee->mois_personnalises) : 'Aucun mois défini' }}
              </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
              @if ($annee->est_active)
                <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-200">Active</span>
              @endif
              @if ($annee->est_cloturee)
                <span class="rounded-full bg-slate-700/60 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200">Clôturée</span>
              @endif
              @if (! $annee->est_active && ! $annee->est_cloturee)
                <span class="rounded-full bg-sky-500/20 px-3 py-1 text-xs font-semibold text-sky-700 dark:text-sky-200">Disponible</span>
              @endif
            </div>
          </div>
          <div class="mt-4 flex flex-wrap gap-3">
            @if (! $annee->est_active && ! $annee->est_cloturee)
              <button wire:click="activer({{ $annee->id }})" type="button" class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-200">
                Activer
              </button>
            @endif
            @if (! $annee->est_cloturee)
              <button wire:click="cloturer({{ $annee->id }})" type="button" class="rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-2 text-xs font-semibold text-rose-700 dark:text-rose-200">
                Clôturer
              </button>
            @endif
          </div>
        </div>
      @empty
        <div class="rounded-3xl border border-dashed border-slate-200 dark:border-slate-700 p-6 text-center text-sm text-slate-600 dark:text-slate-400">
          Aucune année scolaire enregistrée pour le moment.
        </div>
      @endforelse
    </div>
  </section>
</div>
