<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class FacturationIndex extends Component
{
    public string $periodeDebut = '';
    public string $periodeFin = '';
    public string $eleveFilter = '';
    public string $statutFilter = '';

    public ?int $factureSelectionneeId = null;

    public array $facturesSource = [];

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

    public function mount(): void
    {
        $this->facturesSource = [
            [
                'id' => 1,
                'eleve' => 'Amina Diallo',
                'periode' => 'Mars 2025',
                'montant_brut' => 35000,
                'workflow' => 'envoyee',
                'remises' => [
                    ['type' => 'pourcentage', 'valeur' => 10, 'commentaire' => 'Fratrie'],
                ],
                'versements' => [
                    ['montant' => 10000, 'date' => '2025-03-05', 'mode' => 'mobile_money', 'reference' => 'MM-2341'],
                ],
            ],
            [
                'id' => 2,
                'eleve' => 'Idrissa Koné',
                'periode' => 'Mars 2025',
                'montant_brut' => 28000,
                'workflow' => 'validee',
                'remises' => [],
                'versements' => [
                    ['montant' => 28000, 'date' => '2025-03-02', 'mode' => 'especes', 'reference' => 'ES-998'],
                ],
            ],
            [
                'id' => 3,
                'eleve' => 'Sokhna Ndiaye',
                'periode' => 'Avril 2025',
                'montant_brut' => 35000,
                'workflow' => 'brouillon',
                'remises' => [
                    ['type' => 'montant', 'valeur' => 5000, 'commentaire' => 'Bourse partielle'],
                ],
                'versements' => [],
            ],
        ];

        $this->factureSelectionneeId = $this->facturesSource[0]['id'] ?? null;
    }

    public function getFacturesProperty(): Collection
    {
        return collect($this->facturesSource)
            ->map(fn (array $facture) => $this->calculerFacture($facture))
            ->when($this->eleveFilter !== '', function (Collection $collection) {
                return $collection->filter(function (array $facture) {
                    return Str::of($facture['eleve'])
                        ->lower()
                        ->contains(Str::of($this->eleveFilter)->lower());
                });
            })
            ->when($this->statutFilter !== '', function (Collection $collection) {
                return $collection->filter(fn (array $facture) => $facture['statut'] === $this->statutFilter);
            })
            ->when($this->periodeDebut !== '' || $this->periodeFin !== '', function (Collection $collection) {
                return $collection->filter(function (array $facture) {
                    $mois = $this->normaliserMois($facture['periode']);
                    if (! $mois) {
                        return true;
                    }

                    $debut = $this->periodeDebut ? $this->normaliserMois($this->periodeDebut) : null;
                    $fin = $this->periodeFin ? $this->normaliserMois($this->periodeFin) : null;

                    if ($debut && $mois < $debut) {
                        return false;
                    }

                    if ($fin && $mois > $fin) {
                        return false;
                    }

                    return true;
                });
            });
    }

    public function getFactureSelectionneeProperty(): ?array
    {
        if (! $this->factureSelectionneeId) {
            return null;
        }

        return $this->facturesSource
            ? collect($this->facturesSource)
                ->map(fn (array $facture) => $this->calculerFacture($facture))
                ->firstWhere('id', $this->factureSelectionneeId)
            : null;
    }

    public function selectionnerFacture(int $factureId): void
    {
        $this->factureSelectionneeId = $factureId;
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

        $this->facturesSource = collect($this->facturesSource)->map(function (array $facture) use ($montant) {
            if ($facture['id'] !== $this->factureSelectionneeId) {
                return $facture;
            }

            $calculee = $this->calculerFacture($facture);
            if ($montant > $calculee['reste_a_payer']) {
                return $facture;
            }

            $facture['versements'][] = [
                'montant' => $montant,
                'date' => $this->versementForm['date'] ?: now()->toDateString(),
                'mode' => $this->versementForm['mode'],
                'reference' => $this->versementForm['reference'],
                'commentaire' => $this->versementForm['commentaire'],
            ];

            return $facture;
        })->all();

        $this->versementForm = [
            'montant' => '',
            'date' => '',
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

        $this->facturesSource = collect($this->facturesSource)->map(function (array $facture) use ($valeur) {
            if ($facture['id'] !== $this->factureSelectionneeId) {
                return $facture;
            }

            $facture['remises'][] = [
                'type' => $this->remiseForm['type'],
                'valeur' => $valeur,
                'commentaire' => $this->remiseForm['commentaire'],
            ];

            return $facture;
        })->all();

        $this->remiseForm = [
            'type' => 'pourcentage',
            'valeur' => '',
            'commentaire' => '',
        ];
    }

    private function calculerFacture(array $facture): array
    {
        $totalRemises = collect($facture['remises'])
            ->sum(function (array $remise) use ($facture) {
                if ($remise['type'] === 'pourcentage') {
                    return (int) round($facture['montant_brut'] * ($remise['valeur'] / 100));
                }

                return (int) $remise['valeur'];
            });

        $netAPayer = max($facture['montant_brut'] - $totalRemises, 0);
        $totalVerse = collect($facture['versements'])->sum('montant');
        $reste = max($netAPayer - $totalVerse, 0);

        $statut = $this->statutAutomatique($facture['workflow'] ?? null, $netAPayer, $totalVerse);

        return array_merge($facture, [
            'total_remises' => $totalRemises,
            'net_a_payer' => $netAPayer,
            'total_verse' => $totalVerse,
            'reste_a_payer' => $reste,
            'statut' => $statut,
        ]);
    }

    private function statutAutomatique(?string $workflow, int $net, int $verse): string
    {
        if ($net <= 0) {
            return 'non_concernee';
        }

        if ($verse <= 0 && in_array($workflow, ['brouillon', 'validee', 'envoyee'], true)) {
            return $workflow;
        }

        if ($verse <= 0) {
            return 'impayee';
        }

        if ($verse < $net) {
            return 'partiellement_payee';
        }

        return 'payee';
    }

    private function normaliserMois(string $periode): ?string
    {
        $valeur = Str::of($periode)->trim()->lower()->replace(['/', '-', '_'], ' ')->__toString();

        if (preg_match('/(\d{4})-(\d{2})/', $valeur, $matches)) {
            return $matches[0];
        }

        if (preg_match('/(\d{4})/', $valeur, $matches) && preg_match('/(janvier|fevrier|février|mars|avril|mai|juin|juillet|aout|août|septembre|octobre|novembre|decembre|décembre)/', $valeur, $moisMatch)) {
            $mois = [
                'janvier' => '01',
                'fevrier' => '02',
                'février' => '02',
                'mars' => '03',
                'avril' => '04',
                'mai' => '05',
                'juin' => '06',
                'juillet' => '07',
                'aout' => '08',
                'août' => '08',
                'septembre' => '09',
                'octobre' => '10',
                'novembre' => '11',
                'decembre' => '12',
                'décembre' => '12',
            ];

            return $matches[1] . '-' . $mois[$moisMatch[1]];
        }

        return null;
    }

    public function render()
    {
        return view('livewire.facturation-index')->layout('layouts.app', ['header' => 'Facturation']);
    }
}
