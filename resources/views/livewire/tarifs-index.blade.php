<div class="space-y-6">
    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Gestion des tarifs</p>
                <h1 class="mt-2 text-2xl font-semibold text-white">Tarifs de cantine</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-400">
                    Configurez les montants mensuels par classe et la période de validité utilisée lors de la génération des factures.
                </p>
            </div>
            <button class="rounded-2xl border border-emerald-500/40 bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-200 transition hover:bg-emerald-500/30">
                Nouveau tarif
            </button>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Classe</label>
                <input type="text" placeholder="Ex : CM1" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Montant mensuel</label>
                <input type="number" min="0" placeholder="Montant" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.2em] text-slate-500">Période de validité</label>
                <div class="mt-2 grid grid-cols-2 gap-3">
                    <input type="month" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                    <input type="month" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-100" />
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($tarifs as $tarif)
            <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">{{ $tarif['classe'] }}</h2>
                    <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-xs text-emerald-200">Actif</span>
                </div>
                <p class="mt-4 text-2xl font-semibold text-white">{{ number_format($tarif['montant_mensuel'], 0, ',', ' ') }} FCFA</p>
                <p class="mt-2 text-sm text-slate-400">Du {{ $tarif['debut'] }} au {{ $tarif['fin'] }}</p>
                <div class="mt-4 flex gap-2">
                    <button class="rounded-2xl border border-slate-700 px-3 py-1 text-sm text-slate-200 transition hover:border-emerald-400">
                        Modifier
                    </button>
                    <button class="rounded-2xl border border-slate-700 px-3 py-1 text-sm text-slate-200 transition hover:border-rose-400">
                        Archiver
                    </button>
                </div>
            </article>
        @endforeach
    </section>
</div>
