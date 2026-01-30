<?php

namespace App\Livewire;

use App\Models\Eleve;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Remise;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class FacturationIndex extends Component
{
    use WithPagination;

    public string $eleveFilter = '';
    public string $statutFilter = '';
    public int $perPage = 10;

    public ?int $factureSelectionneeId = null;
    public ?int $eleveSelectionneId = null;

    public array $versementForm = [
        'montant' => '',
        'date' => '',
        'mode' => 'especes',
        'reference' => '',
        'commentaire' => '',
    ];

    public array $remiseForm = [
        'type' => 'pourcentage',
        'valeur' => '',
        'commentaire' => '',
    ];

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $factureInitiale = $this->factures->first();
        $this->factureSelectionneeId = $factureInitiale['id'] ?? null;
        $this->eleveSelectionneId = $factureInitiale['eleve_id'] ?? null;
        $this->versementForm['date'] = now()->toDateString();
    }

    public function getFacturesProperty(): Collection
    {
        $query = Facture::query()
            ->with(['eleve', 'paiements', 'remises'])
            ->orderByDesc('mois');

        if ($this->eleveFilter !== '') {
            $query->where('eleve_id', $this->eleveFilter);
        }

        $factures = $query->get()->map(fn (Facture $facture) => $this->transformerFacture($facture));

        if ($this->statutFilter !== '') {
            $factures = $factures->filter(fn (array $facture) => $facture['statut'] === $this->statutFilter);
        }

        return $factures->values();
    }

    public function getFacturesPaginatedProperty(): LengthAwarePaginator
    {
        $factures = $this->factures;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $items = $factures->forPage($page, $this->perPage)->values();

        return new LengthAwarePaginator($items, $factures->count(), $this->perPage, $page, [
            'path' => request()->url(),
            'pageName' => 'page',
        ]);
    }

    public function getElevesProperty(): Collection
    {
        return Eleve::query()
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get()
            ->map(function (Eleve $eleve) {
                $nomEleve = trim(($eleve->prenom ?? '') . ' ' . ($eleve->nom ?? ''));

                return [
                    'id' => $eleve->id,
                    'nom' => $nomEleve !== '' ? $nomEleve : ($eleve->nom ?? 'Élève'),
                ];
            });
    }

    public function getFactureSelectionneeProperty(): ?array
    {
        if (! $this->factureSelectionneeId) {
            return null;
        }

        $facture = Facture::query()
            ->with(['eleve', 'paiements', 'remises'])
            ->find($this->factureSelectionneeId);

        return $facture ? $this->transformerFacture($facture) : null;
    }

    public function getEleveSelectionneProperty(): ?array
    {
        if (! $this->eleveSelectionneId) {
            return null;
        }

        $factures = Facture::query()
            ->with(['eleve', 'paiements', 'remises'])
            ->where('eleve_id', $this->eleveSelectionneId)
            ->orderByDesc('mois')
            ->get()
            ->map(fn (Facture $facture) => $this->transformerFacture($facture))
            ->values();

        if ($factures->isEmpty()) {
            return null;
        }

        $totalBrut = $factures->sum('montant_brut');
        $totalRemises = $factures->sum('total_remises');
        $totalNet = $factures->sum('net_a_payer');
        $totalVerse = $factures->sum('total_verse');
        $totalReste = $factures->sum('reste_a_payer');

        return [
            'id' => $this->eleveSelectionneId,
            'nom' => $factures->first()['eleve'],
            'factures' => $factures->all(),
            'total_brut' => $totalBrut,
            'total_remises' => $totalRemises,
            'total_net' => $totalNet,
            'total_verse' => $totalVerse,
            'total_reste' => $totalReste,
        ];
    }

    public function selectionnerFacture(int $factureId): void
    {
        $this->factureSelectionneeId = $factureId;

        $facture = Facture::query()->find($factureId);
        $this->eleveSelectionneId = $facture?->eleve_id;
        $this->versementForm['date'] = now()->toDateString();
    }

    public function appliquerFiltres(): void
    {
        $this->resetPage();
    }

    public function updatedFactureSelectionneeId(?int $value): void
    {
        if (! $value) {
            return;
        }

        $facture = Facture::query()->find($value);
        $this->eleveSelectionneId = $facture?->eleve_id;
    }

    public function updatedEleveSelectionneId(?int $value): void
    {
        if (! $value) {
            return;
        }

        $facture = Facture::query()
            ->where('eleve_id', $value)
            ->orderByDesc('mois')
            ->first();

        $this->factureSelectionneeId = $facture?->id;
    }

    public function ajouterVersement(): void
    {
        if (! $this->factureSelectionneeId) {
            return;
        }

        $montant = (int) $this->versementForm['montant'];
        if ($montant <= 0) {
            return;
        }

        $facture = Facture::query()->with(['paiements', 'remises'])->find($this->factureSelectionneeId);
        if (! $facture) {
            return;
        }

        $snapshot = $this->transformerFacture($facture);
        if ($montant > $snapshot['reste_a_payer']) {
            return;
        }

        Paiement::create([
            'eleve_id' => $facture->eleve_id,
            'facture_id' => $facture->id,
            'mois' => $facture->mois,
            'montant' => $montant,
            'date_paiement' => $this->versementForm['date'] ?: now()->toDateString(),
            'mode_paiement' => $this->versementForm['mode'],
            'reference' => $this->versementForm['reference'],
            'commentaire' => $this->versementForm['commentaire'] ?: null,
        ]);

        $this->recalculerFacture($facture);

        $this->versementForm = [
            'montant' => '',
            'date' => now()->toDateString(),
            'mode' => 'especes',
            'reference' => '',
            'commentaire' => '',
        ];
    }

    public function ajouterRemise(): void
    {
        if (! $this->factureSelectionneeId) {
            return;
        }

        $valeur = (int) $this->remiseForm['valeur'];
        if ($valeur <= 0) {
            return;
        }

        $facture = Facture::query()->with('remises')->find($this->factureSelectionneeId);
        if (! $facture) {
            return;
        }

        if ($this->remiseForm['type'] === 'pourcentage') {
            $valeur = min($valeur, 100);
        }

        Remise::create([
            'eleve_id' => $facture->eleve_id,
            'facture_id' => $facture->id,
            'libelle' => $this->remiseForm['commentaire'] ?: 'Remise facture',
            'type_remise' => $this->remiseForm['type'] === 'pourcentage' ? 'pourcentage' : 'fixe',
            'valeur' => $valeur,
            'actif' => true,
            'commentaire' => $this->remiseForm['commentaire'] ?: null,
        ]);

        $this->recalculerFacture($facture);

        $this->remiseForm = [
            'type' => 'pourcentage',
            'valeur' => '',
            'commentaire' => '',
        ];
    }

    private function transformerFacture(Facture $facture): array
    {
        $montantBrut = (int) round($facture->montant_mensuel);
        $remises = $facture->remises->map(fn (Remise $remise) => [
            'type' => $remise->type_remise,
            'valeur' => (int) round($remise->valeur),
            'commentaire' => $remise->commentaire ?: $remise->libelle,
            'date' => $remise->created_at?->toDateString(),
        ]);

        if ($remises->isEmpty() && $facture->montant_remise > 0) {
            $remises = collect([[
                'type' => 'fixe',
                'valeur' => (int) round($facture->montant_remise),
                'commentaire' => 'Remise facture',
                'date' => $facture->updated_at?->toDateString(),
            ]]);
        }

        $totalRemises = $remises->sum(function (array $remise) use ($montantBrut) {
            if ($remise['type'] === 'pourcentage') {
                return (int) round($montantBrut * ($remise['valeur'] / 100));
            }

            return (int) $remise['valeur'];
        });

        $netAPayer = max($montantBrut - $totalRemises, 0);
        $totalVerse = (int) round($facture->paiements->sum('montant'));
        $reste = max($netAPayer - $totalVerse, 0);
        $statut = $this->statutAutomatique($netAPayer, $totalVerse);

        return [
            'id' => $facture->id,
            'eleve_id' => $facture->eleve_id,
            'eleve' => trim(($facture->eleve?->prenom ?? '') . ' ' . ($facture->eleve?->nom ?? '')) ?: 'Élève',
            'periode' => $facture->mois?->translatedFormat('m/Y') ?? '',
            'montant_brut' => $montantBrut,
            'total_remises' => $totalRemises,
            'net_a_payer' => $netAPayer,
            'total_verse' => $totalVerse,
            'reste_a_payer' => $reste,
            'statut' => $statut,
            'versements' => $facture->paiements->map(fn (Paiement $paiement) => [
                'montant' => (int) round($paiement->montant),
                'date' => $paiement->date_paiement?->toDateString(),
                'mode' => $paiement->mode_paiement,
                'reference' => $paiement->reference,
                'commentaire' => $paiement->commentaire,
            ])->all(),
            'remises' => $remises->all(),
        ];
    }

    private function statutAutomatique(int $net, int $verse): string
    {
        if ($net <= 0) {
            return 'a_jour';
        }

        if ($verse <= 0) {
            return 'retard';
        }

        if ($verse < $net) {
            return 'partiel';
        }

        return 'a_jour';
    }

    public function statutBadge(?string $statut): array
    {
        return match ($statut) {
            'a_jour' => [
                'label' => 'Soldée',
                'classes' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200',
                'dot' => 'bg-emerald-400',
            ],
            'partiel' => [
                'label' => 'Partiel',
                'classes' => 'bg-amber-500/10 text-amber-700 dark:text-amber-200',
                'dot' => 'bg-amber-300',
            ],
            'retard' => [
                'label' => 'Impayée',
                'classes' => 'bg-rose-500/10 text-rose-700 dark:text-rose-200',
                'dot' => 'bg-rose-400',
            ],
            default => [
                'label' => 'Autre',
                'classes' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
                'dot' => 'bg-slate-400',
            ],
        };
    }

    public function render()
    {
        return view('livewire.facturation-index')->layout('layouts.app', ['header' => 'Facturation']);
    }

    private function recalculerFacture(Facture $facture): void
    {
        $facture->load(['paiements', 'remises']);

        $montantBrut = (int) round($facture->montant_mensuel);
        $totalRemises = $facture->remises->sum(function (Remise $remise) use ($montantBrut) {
            if ($remise->type_remise === 'pourcentage') {
                return (int) round($montantBrut * ($remise->valeur / 100));
            }

            return (int) round($remise->valeur);
        });

        $netAPayer = max($montantBrut - $totalRemises, 0);
        $totalVerse = (int) round($facture->paiements->sum('montant'));
        $statut = $this->statutAutomatique($netAPayer, $totalVerse);

        $facture->update([
            'montant_remise' => $totalRemises,
            'montant_total' => $netAPayer,
            'statut' => $statut,
        ]);
    }
}
