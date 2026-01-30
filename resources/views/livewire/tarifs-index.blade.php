<div class="space-y-6">
    <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Gestion des tarifs</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Tarifs de cantine</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                    Configurez les montants mensuels par classe et la période de validité utilisée lors de la génération des factures.
                </p>
            </div>
            <button form="tarif-form" type="submit" class="rounded-2xl border border-emerald-500/40 bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/30 dark:text-emerald-200">
                Nouveau tarif
            </button>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-slate-800 dark:bg-slate-900/60">
        <form id="tarif-form" wire:submit.prevent="ajouterTarif">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Classe</label>
                    <input wire:model="classe" type="text" placeholder="Ex : CM1" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500" />
                    @error('classe')
                        <p class="mt-2 text-xs text-rose-500 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Montant mensuel</label>
                    <input wire:model="montant" type="number" min="0" placeholder="Montant" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500" />
                    @error('montant')
                        <p class="mt-2 text-xs text-rose-500 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Période de validité</label>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <input wire:model="debut" type="month" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                        <input wire:model="fin" type="month" class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                    </div>
                    @error('debut')
                        <p class="mt-2 text-xs text-rose-500 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                    @error('fin')
                        <p class="mt-2 text-xs text-rose-500 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($tarifs as $tarif)
            <article class="rounded-3xl border border-slate-200 bg-white/90 p-6 dark:border-slate-800 dark:bg-slate-900/70">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $tarif['classe'] }}</h2>
                    <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-xs text-emerald-700 dark:text-emerald-200">Actif</span>
                </div>
                <p class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($tarif['montant_mensuel'], 0, ',', ' ') }} FCFA</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Du {{ $tarif['debut'] }} au {{ $tarif['fin'] }}</p>
                <div class="mt-4 flex gap-2">
                    <button class="rounded-2xl border border-slate-300 px-3 py-1 text-sm text-slate-600 transition hover:border-emerald-400 dark:border-slate-700 dark:text-slate-200">
                        Modifier
                    </button>
                    <button class="rounded-2xl border border-slate-300 px-3 py-1 text-sm text-slate-600 transition hover:border-rose-400 dark:border-slate-700 dark:text-slate-200">
                        Archiver
                    </button>
                </div>
            </article>
        @endforeach
    </section>
</div>
