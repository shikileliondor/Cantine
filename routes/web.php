<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ClasseShow;
use App\Livewire\ClassesIndex;
use App\Livewire\EleveForm;
use App\Livewire\ElevesIndex;
use App\Livewire\FacturationIndex;
use App\Livewire\ParametresIndex;
use App\Livewire\TarifsIndex;
use App\Models\AnneeScolaire;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Remise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $now = now();
    $months = collect(range(6, 0))
        ->map(fn (int $offset) => $now->copy()->subMonths($offset)->startOfMonth());

    $revenusParMois = $months->map(function ($month) {
        return Paiement::query()
            ->whereBetween('date_paiement', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->sum('montant');
    });

    $encaissementsTotal = Paiement::query()->sum('montant');
    $encaissementsMois = $revenusParMois->last() ?? 0;
    $encaissementsMoisPrecedent = $revenusParMois->get($revenusParMois->count() - 2, 0);
    $encaissementsEvolution = $encaissementsMoisPrecedent > 0
        ? round((($encaissementsMois - $encaissementsMoisPrecedent) / $encaissementsMoisPrecedent) * 100)
        : null;

    $facturesTotal = Facture::query()->count();
    $facturesDuMois = Facture::query()
        ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
        ->count();
    $facturesRetard = Facture::query()->where('statut', 'retard')->count();
    $remisesActives = Remise::query()->where('actif', true)->count();

    $repartition = Facture::query()
        ->select('statut', DB::raw('count(*) as total'))
        ->whereIn('statut', ['a_jour', 'partiel', 'retard'])
        ->groupBy('statut')
        ->pluck('total', 'statut');
    $repartitionTotal = $repartition->sum();
    $repartitionData = [
        'a_jour' => (int) ($repartition['a_jour'] ?? 0),
        'partiel' => (int) ($repartition['partiel'] ?? 0),
        'retard' => (int) ($repartition['retard'] ?? 0),
    ];
    $repartitionPercentages = collect($repartitionData)->map(function ($count) use ($repartitionTotal) {
        if ($repartitionTotal === 0) {
            return 0;
        }

        return round(($count / $repartitionTotal) * 100);
    });

    $statutStyles = [
        'a_jour' => ['label' => 'Payé', 'class' => 'bg-emerald-500/20 text-emerald-300'],
        'partiel' => ['label' => 'Partiel', 'class' => 'bg-amber-500/20 text-amber-300'],
        'retard' => ['label' => 'Retard', 'class' => 'bg-rose-500/20 text-rose-300'],
    ];

    $derniersPaiements = Paiement::query()
        ->with(['eleve.classe', 'facture'])
        ->orderByDesc('date_paiement')
        ->limit(3)
        ->get()
        ->map(function (Paiement $paiement) use ($statutStyles) {
            $eleve = $paiement->eleve;
            $classe = $eleve?->classe;
            $statut = $paiement->facture?->statut ?? 'a_jour';
            $style = $statutStyles[$statut] ?? $statutStyles['a_jour'];
            $classeLabel = $classe
                ? trim(collect([$classe->nom, $classe->niveau])->filter()->implode(' '))
                : 'Classe non définie';

            return [
                'eleve_nom' => trim(($eleve->prenom ?? '').' '.($eleve->nom ?? '')) ?: 'Élève inconnu',
                'classe_label' => $classeLabel,
                'montant' => $paiement->montant,
                'statut_label' => $style['label'],
                'statut_class' => $style['class'],
            ];
        });

    $alertes = [
        [
            'titre' => 'Relances à envoyer',
            'detail' => $facturesRetard > 0
                ? $facturesRetard.' factures en retard à relancer.'
                : 'Aucune relance en attente.',
        ],
        [
            'titre' => 'Remises actives',
            'detail' => $remisesActives > 0
                ? $remisesActives.' remises actives dans les dossiers.'
                : 'Aucune remise active enregistrée.',
        ],
        [
            'titre' => 'Factures du mois',
            'detail' => $facturesDuMois > 0
                ? $facturesDuMois.' factures générées ce mois-ci.'
                : 'Aucune facture générée ce mois-ci.',
        ],
    ];

    return view('dashboard', [
        'anneeActive' => AnneeScolaire::active(),
        'stats' => [
            'encaissements_total' => $encaissementsTotal,
            'encaissements_evolution' => $encaissementsEvolution,
            'factures_total' => $facturesTotal,
            'factures_mois' => $facturesDuMois,
            'retards_total' => $facturesRetard,
            'remises_actives' => $remisesActives,
        ],
        'charts' => [
            'revenus' => [
                'labels' => $months->map(fn ($month) => $month->locale(app()->getLocale())->translatedFormat('M')),
                'data' => $revenusParMois->map(fn ($montant) => (float) $montant),
            ],
            'repartition' => [
                'labels' => ['À jour', 'Partiel', 'Retard'],
                'data' => array_values($repartitionData),
                'percentages' => $repartitionPercentages->values(),
            ],
        ],
        'derniersPaiements' => $derniersPaiements,
        'alertes' => $alertes,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/eleves', ElevesIndex::class)->name('eleves.index');
    Route::get('/eleves/creer', EleveForm::class)->name('eleves.create');
    Route::get('/eleves/{eleve}/modifier', EleveForm::class)->name('eleves.edit');

    Route::get('/eleves/classes', ClassesIndex::class)->name('eleves.classes.index');
    Route::get('/eleves/classes/{classe}', ClasseShow::class)->name('eleves.classes.show');

    Route::get('/tarifs', TarifsIndex::class)->name('tarifs.index');
    Route::get('/facturation', FacturationIndex::class)->name('facturation.index');
    Route::get('/parametres', ParametresIndex::class)->name('parametres.index');
});

require __DIR__.'/auth.php';
