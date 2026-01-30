<div class="space-y-8">
  <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Classe</p>
      <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $classe->nom }}</h1>
      <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Niveau : {{ $classe->niveau ?? 'Non défini' }} · Année scolaire : {{ $classe->annee_scolaire ?? 'Non définie' }}</p>
    </div>
    <a href="{{ route('eleves.classes.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 transition hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300">
      Retour classes
    </a>
  </div>

  <div class="grid gap-4 md:grid-cols-2">
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
      <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Élèves inscrits</p>
      <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $classe->eleves->count() }}</p>
    </div>
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60 p-6">
      <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Statut cantine</p>
      <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $this->stats['payes'] }} payés / {{ $this->stats['impayes'] }} en attente</p>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Basé sur la dernière facture connue.</p>
    </div>
  </div>

  <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/60">
    <div class="border-b border-slate-200 dark:border-slate-800 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Liste des élèves</h2>
    </div>
    <div class="divide-y divide-slate-800">
      @forelse ($classe->eleves as $eleve)
        <div class="flex flex-col gap-3 px-6 py-4 md:flex-row md:items-center md:justify-between">
          <div>
            <p class="text-base font-semibold text-slate-900 dark:text-white">{{ $eleve->prenom }} {{ $eleve->nom }}</p>
            <p class="text-xs text-slate-500">Statut : {{ ucfirst($eleve->statut) }}</p>
          </div>
          <div class="flex flex-wrap gap-2">
            <x-cantine.badge tone="{{ $eleve->statut === 'actif' ? 'emerald' : 'rose' }}">{{ ucfirst($eleve->statut) }}</x-cantine.badge>
            <x-cantine.badge tone="slate">Cantine {{ $eleve->latestFacture?->statut ?? 'à jour' }}</x-cantine.badge>
          </div>
        </div>
      @empty
        <div class="px-6 py-6 text-sm text-slate-600 dark:text-slate-400">Aucun élève dans cette classe.</div>
      @endforelse
    </div>
  </div>
</div>
