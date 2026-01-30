<div class="space-y-8">
  <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Module unifié</p>
      <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Classes</h1>
      <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Structure scolaire et effectifs en temps réel.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <button type="button" wire:click="openForm" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
        <span class="text-lg">＋</span>
        Nouvelle classe
      </button>
      <a href="{{ route('eleves.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 transition hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300">
        Retour élèves
      </a>
    </div>
  </div>

  <div class="flex flex-wrap items-center gap-3 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-2">
    <a href="{{ route('eleves.index') }}" class="rounded-2xl px-4 py-2 text-sm font-semibold {{ request()->routeIs('eleves.index') ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-200' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
      Élèves
    </a>
    <a href="{{ route('eleves.classes.index') }}" class="rounded-2xl px-4 py-2 text-sm font-semibold {{ request()->routeIs('eleves.classes.index') ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-200' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
      Classes
    </a>
  </div>

  @if (session('status'))
    <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-200">
      {{ session('status') }}
    </div>
  @endif

  <div class="grid gap-4 lg:grid-cols-3">
    @foreach ($classes as $classe)
      <div wire:key="classe-card-{{ $classe->id }}" class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ $classe->niveau ?? 'Niveau' }}</p>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $classe->nom }}</h2>
          </div>
          <x-cantine.badge tone="violet">{{ $classe->eleves_count }} élèves</x-cantine.badge>
        </div>
        <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">Année scolaire : {{ $classe->annee_scolaire ?? 'Non définie' }}</p>
        <div class="mt-6 flex flex-wrap gap-2">
          <a href="{{ route('eleves.classes.show', $classe) }}" class="rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 transition hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300">
            Voir élèves
          </a>
          <button type="button" wire:click="openForm({{ $classe->id }})" class="rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 transition hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300">
            Modifier
          </button>
          <button
            type="button"
            x-data
            x-on:click="confirm('Supprimer cette classe ?') && $wire.delete({{ $classe->id }})"
            class="rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 transition hover:border-rose-400 hover:text-rose-600 dark:hover:text-rose-300"
          >
            Supprimer
          </button>
        </div>
      </div>
    @endforeach
  </div>

  @if ($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 dark:bg-slate-950/80 px-4 py-6">
      <div class="w-full max-w-xl rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Classe</p>
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $editingId ? 'Modifier la classe' : 'Nouvelle classe' }}</h3>
          </div>
          <button type="button" wire:click="closeForm" class="rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-2 text-sm text-slate-600 dark:text-slate-300">
            Fermer
          </button>
        </div>

        <div class="mt-6 space-y-4">
          <div>
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nom</label>
            <input type="text" wire:model="nom" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3 text-sm text-slate-700 dark:text-slate-200 focus:border-emerald-400 focus:outline-none" />
            @error('nom') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Niveau</label>
            <input type="text" wire:model="niveau" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3 text-sm text-slate-700 dark:text-slate-200 focus:border-emerald-400 focus:outline-none" />
            @error('niveau') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Année scolaire</label>
            <input type="text" wire:model="annee_scolaire" placeholder="2024-2025" class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3 text-sm text-slate-700 dark:text-slate-200 focus:border-emerald-400 focus:outline-none" />
            @error('annee_scolaire') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <button type="button" wire:click="closeForm" class="rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-2 text-sm text-slate-600 dark:text-slate-300">
            Annuler
          </button>
          <button type="button" wire:click="save" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950">
            Enregistrer
          </button>
        </div>
      </div>
    </div>
  @endif
</div>
