<x-app-layout>
    <x-slot name="header">
        Notes &amp; Bulletins
    </x-slot>

    @php($anneeActive = \App\Models\AnneeScolaire::active())

    <div class="space-y-10">
        <section class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tableau de bord</p>
                        <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Pilotage des notes et bulletins</h1>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Vue synthétique des résultats et de l'activité d'évaluations, sans saisie directe.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-emerald-500/30">
                            Nouvelle évaluation
                        </button>
                        <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-800 dark:text-slate-200">
                            Calculer moyennes
                        </button>
                        <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-800 dark:text-slate-200">
                            Générer bulletins
                        </button>
                    </div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Moyenne générale</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">À calculer par classe</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Basée sur les évaluations validées.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Évolution trimestrielle</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">Historique à générer</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Comparaison entre trimestres.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Top élèves</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">Classement indisponible</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Généré après calcul des moyennes.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Évaluations réalisées</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">Par classe</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Suivi des évaluations créées.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Alertes</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">Surveillance active</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Classes ou matières sans évaluations.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Rappels</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Règles clés</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>Impossible de saisir des notes sans évaluation créée.</li>
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>Le trimestre actif est verrouillé pendant la saisie.</li>
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>Les bulletins s'appuient sur les évaluations validées uniquement.</li>
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>L'historique des évaluations reste accessible à tout moment.</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Contexte actif</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Période scolaire</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <div class="flex items-center justify-between gap-3">
                            <span>Année scolaire</span>
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $anneeActive?->libelle ?? 'À définir dans les paramètres' }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span>Trimestre actif</span>
                            <span class="font-semibold text-slate-900 dark:text-white">Déterminé automatiquement</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.3fr,1fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Création d'une évaluation</p>
                        <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Configuration guidée</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">L'évaluation est créée sans notes, puis prête pour la saisie.</p>
                    </div>
                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                        Trimestre actif verrouillé
                    </span>
                </div>

                <div class="mt-6 space-y-6">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Étape 1 — Contexte</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Année scolaire</label>
                                <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                                    {{ $anneeActive?->libelle ?? 'Récupérée depuis les paramètres' }}
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Trimestre actif</label>
                                <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                                    Sélection automatique
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Classe</label>
                                <select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                                    <option selected>Choisir une classe</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Type d'évaluation</label>
                                <select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                                    <option selected>Devoir, interrogation, examen</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Étape 2 — Paramètres</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Matière</label>
                                <select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                                    <option selected>Choisir une matière</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Date de l'évaluation</label>
                                <input
                                    type="date"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200"
                                />
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Barème</label>
                                <input
                                    type="text"
                                    placeholder="/"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200"
                                />
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Coefficient</label>
                                <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                                    Automatique ou en lecture seule
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-emerald-500/30">
                                Créer l'évaluation
                            </button>
                            <p class="text-xs text-slate-500 dark:text-slate-400">La saisie des notes est activée après création.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Gestion multi-évaluations</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Même classe, mêmes matières</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>Plusieurs évaluations autorisées par matière et trimestre.</li>
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>Chaque évaluation reste indépendante.</li>
                        <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>Les moyennes s'appuient uniquement sur les évaluations validées.</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Calculs & exploitation</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Validation manuelle</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <p>Le calcul des moyennes est déclenché manuellement pour garder un contrôle total.</p>
                        <p>Une fois les résultats validés, les notes deviennent non modifiables.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Saisie des notes</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Tableau de notes par élève</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Les notes sont liées individuellement à chaque évaluation.</p>
                </div>
                <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-800 dark:text-slate-200">
                    Enregistrer les notes
                </button>
            </div>
            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.2em] text-slate-500 dark:bg-slate-950/40 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Note</th>
                            <th class="px-4 py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-600 dark:divide-slate-800 dark:text-slate-300">
                        <tr>
                            <td class="px-4 py-4" colspan="3">
                                <div class="flex flex-col gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <span>Aucun élève chargé.</span>
                                    <span>Sélectionnez une évaluation pour lancer la saisie.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/60">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Vue classe</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Historique des évaluations</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Historique conservé pour toutes les évaluations.</p>
                </div>
                <div class="flex items-center gap-2">
                    <select class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-200">
                        <option selected>Choisir une classe</option>
                    </select>
                    <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-400 hover:text-emerald-600 dark:border-slate-800 dark:text-slate-200">
                        Voir la classe
                    </button>
                </div>
            </div>
            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.2em] text-slate-500 dark:bg-slate-950/40 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Matière</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-600 dark:divide-slate-800 dark:text-slate-300">
                        <tr>
                            <td class="px-4 py-4" colspan="5">
                                <div class="flex flex-col gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <span>Aucune évaluation disponible.</span>
                                    <span>Les évaluations validées apparaîtront ici avec leur statut.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
