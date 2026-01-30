<?php

namespace App\Livewire;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Facture;
use App\Models\NoteEleve;
use App\Models\Paiement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class FacturationIndex extends Component
{
    use WithPagination;

    public string $periodeDebut = '';
    public string $periodeFin = '';
    public string $eleveFilter = '';
    public string $statutFilter = '';
    public string $nomRecherche = '';
    public string $classeRecherche = '';
    public string $classeFilter = '';
    public int $perPage = 10;

    public ?int $factureSelectionneeId = null;
    public ?int $eleveSelectionneId = null;
    public ?int $noteEleveId = null;

    public string $noteType = 'interne';
    public string $noteContenu = '';

    public array $facturesSource = [];
    public array $moisDisponibles = [];
    public array $elevesSource = [];

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

    public string $remiseRapideType = 'pourcentage';
    public string $remiseRapideValeur = '10';

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'noteType' => 'required|string|max:50',
        'noteContenu' => 'required|string|min:3',
    ];

    public function mount(): void
    {
        $anneeActive = AnneeScolaire::active();
        $anneeDebut = $anneeActive?->annee_debut ?? (int) now()->format('Y');
        $anneeFin = $anneeActive?->annee_fin ?? $anneeDebut + 1;
        $moisBase = $anneeActive?->mois_personnalises ?? [
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre',
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
        ];

        $this->moisDisponibles = collect($moisBase)
            ->map(fn (string $mois) => $this->formaterPeriode($mois, $anneeDebut, $anneeFin))
            ->values()
            ->all();

        $this->chargerFacturesSource();

        $factureInitiale = $this->factures->first(fn (array $facture) => ! empty($facture['id']));
        $this->factureSelectionneeId = $factureInitiale['id'] ?? null;
        $this->eleveSelectionneId = $factureInitiale['eleve_id'] ?? null;
    }

    public function getClassesProperty(): Collection
    {
        return Classe::query()
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();
    }

    public function getFacturesProperty(): Collection
    {
        $facturesFiltrees = collect($this->facturesSource)
            ->map(fn (array $facture) => $this->calculerFacture($facture))
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
            })
            ->when($this->statutFilter !== '', function (Collection $collection) {
                return $collection->filter(fn (array $facture) => $facture['statut'] === $this->statutFilter);
            });

        $afficherLignesVides = $this->periodeDebut === '' && $this->periodeFin === '' && $this->statutFilter === '';

        $eleves = collect($this->elevesSource)
            ->when($this->classeFilter !== '', function (Collection $collection) {
                return $collection->filter(fn (array $eleve) => (string) $eleve['classe_id'] === (string) $this->classeFilter);
            })
            ->when($this->eleveFilter !== '', function (Collection $collection) {
                return $collection->filter(function (array $eleve) {
                    return Str::of($eleve['nom'])
                        ->lower()
                        ->contains(Str::of($this->eleveFilter)->lower());
                });
            });

        return $eleves
            ->map(function (array $eleve) use ($facturesFiltrees, $afficherLignesVides) {
                $facture = $facturesFiltrees
                    ->where('eleve_id', $eleve['id'])
                    ->sortByDesc(fn (array $factureItem) => $factureItem['mois_sort'] ?? '')
                    ->first();

                if ($facture) {
                    return $facture;
                }

                if (! $afficherLignesVides) {
                    return null;
                }

                return [
                    'id' => null,
                    'eleve_id' => $eleve['id'],
                    'eleve' => $eleve['nom'],
                    'classe' => $eleve['classe'],
                    'classe_id' => $eleve['classe_id'],
                    'periode' => 'Aucune facture',
                    'mois_sort' => null,
                    'montant_brut' => 0,
                    'total_remises' => 0,
                    'net_a_payer' => 0,
                    'total_verse' => 0,
                    'reste_a_payer' => 0,
                    'statut' => 'a_creer',
                    'workflow' => null,
                    'remises' => [],
                    'versements' => [],
                ];
            })
            ->filter()
            ->values();
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
        return collect($this->elevesSource);
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

    public function getEleveSelectionneProperty(): ?array
    {
        if (! $this->eleveSelectionneId) {
            return null;
        }

        $factures = collect($this->facturesSource)
            ->filter(fn (array $facture) => $facture['eleve_id'] === $this->eleveSelectionneId)
            ->map(fn (array $facture) => $this->calculerFacture($facture))
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

        $facture = collect($this->facturesSource)->firstWhere('id', $factureId);
        if ($facture) {
            $this->eleveSelectionneId = $facture['eleve_id'];
        }
    }

    public function appliquerFiltres(): void
    {
        $this->eleveFilter = trim($this->nomRecherche);
        $this->classeFilter = $this->classeRecherche;
        $this->resetPage();
    }

    public function openNoteModal(int $eleveId): void
    {
        $this->resetValidation();
        $this->noteEleveId = $eleveId;
        $this->noteType = 'interne';
        $this->noteContenu = '';
    }

    public function saveNote(): void
    {
        $this->validate();

        if (! $this->noteEleveId) {
            return;
        }

        NoteEleve::create([
            'eleve_id' => $this->noteEleveId,
            'type_note' => $this->noteType,
            'contenu' => $this->noteContenu,
        ]);

        $this->noteEleveId = null;
        $this->noteContenu = '';
    }

    public function updatedFactureSelectionneeId(?int $value): void
    {
        if (! $value) {
            return;
        }

        $facture = collect($this->facturesSource)->firstWhere('id', $value);
        if ($facture) {
            $this->eleveSelectionneId = $facture['eleve_id'];
        }
    }

    public function updatedEleveSelectionneId(?int $value): void
    {
        if (! $value) {
            return;
        }

        $facture = collect($this->facturesSource)
            ->filter(fn (array $facture) => $facture['eleve_id'] === $value)
            ->sortByDesc(fn (array $facture) => $facture['mois_sort'] ?? '')
            ->first();
        if ($facture) {
            $this->factureSelectionneeId = $facture['id'];
            $this->eleveFilter = $facture['eleve'];
        }
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

    public function encaisserSolde(): void
    {
        if (! $this->factureSelectionneeId) {
            return;
        }

        $facture = $this->factureSelectionnee;
        if (! $facture) {
            return;
        }

        $reste = (int) $facture['reste_a_payer'];
        if ($reste <= 0) {
            return;
        }

        $this->versementForm = [
            'montant' => (string) $reste,
            'date' => now()->toDateString(),
            'mode' => $this->versementForm['mode'] ?: 'especes',
            'reference' => $this->versementForm['reference'],
            'commentaire' => 'Encaissement du reste à payer',
        ];

        $this->ajouterVersement();
    }

    public function appliquerRemiseRapide(): void
    {
        if (! $this->factureSelectionneeId) {
            return;
        }

        $facture = $this->factureSelectionnee;
        if (! $facture) {
            return;
        }

        $valeur = (int) $this->remiseRapideValeur;
        if ($valeur <= 0) {
            return;
        }

        if ($this->remiseRapideType === 'pourcentage') {
            $valeur = min($valeur, 100);
        } else {
            $valeur = min($valeur, (int) $facture['montant_brut']);
        }

        $this->facturesSource = collect($this->facturesSource)->map(function (array $factureItem) use ($valeur) {
            if ($factureItem['id'] !== $this->factureSelectionneeId) {
                return $factureItem;
            }

            $factureItem['remises'][] = [
                'type' => $this->remiseRapideType,
                'valeur' => $valeur,
                'commentaire' => 'Remise rapide',
            ];

            return $factureItem;
        })->all();
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

    public function statutBadge(?string $statut): array
    {
        return match ($statut) {
            'payee' => [
                'label' => 'Soldé',
                'classes' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200',
                'dot' => 'bg-emerald-400',
            ],
            'partiellement_payee' => [
                'label' => 'Partiel',
                'classes' => 'bg-amber-500/10 text-amber-700 dark:text-amber-200',
                'dot' => 'bg-amber-300',
            ],
            'impayee' => [
                'label' => 'Impayé',
                'classes' => 'bg-rose-500/10 text-rose-700 dark:text-rose-200',
                'dot' => 'bg-rose-400',
            ],
            'non_concernee' => [
                'label' => 'Non concerné',
                'classes' => 'bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
                'dot' => 'bg-slate-400',
            ],
            'a_creer' => [
                'label' => 'À créer',
                'classes' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
                'dot' => 'bg-slate-400',
            ],
            default => [
                'label' => 'Autre',
                'classes' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
                'dot' => 'bg-slate-400',
            ],
        };
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

    private function formaterPeriode(string $mois, int $anneeDebut, int $anneeFin): string
    {
        $moisNormalise = Str::of($mois)->trim()->lower()->__toString();
        $moisDebutAnnee = ['septembre', 'octobre', 'novembre', 'decembre', 'décembre'];
        $annee = in_array($moisNormalise, $moisDebutAnnee, true) ? $anneeDebut : $anneeFin;

        return Str::of($mois)->trim()->ucfirst()->__toString() . ' ' . $annee;
    }

    public function render()
    {
        return view('livewire.facturation-index')->layout('layouts.app', ['header' => 'Facturation']);
    }

    private function chargerFacturesSource(): void
    {
        $this->elevesSource = Eleve::query()
            ->with('classe')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get()
            ->map(function (Eleve $eleve) {
                $nomEleve = trim(($eleve->prenom ?? '') . ' ' . ($eleve->nom ?? ''));

                return [
                    'id' => $eleve->id,
                    'nom' => $nomEleve !== '' ? $nomEleve : ($eleve->nom ?? 'Élève'),
                    'classe' => $eleve->classe?->nom ?? '—',
                    'classe_id' => $eleve->classe_id,
                ];
            })
            ->all();

        $paiements = Paiement::query()
            ->get()
            ->groupBy(function (Paiement $paiement) {
                if ($paiement->facture_id) {
                    return 'facture:' . $paiement->facture_id;
                }

                $moisKey = $paiement->mois?->format('Y-m') ?? 'inconnu';

                return 'eleve:' . $paiement->eleve_id . '|mois:' . $moisKey;
            });

        $this->facturesSource = Facture::query()
            ->with(['eleve.classe'])
            ->orderBy('mois')
            ->get()
            ->map(function (Facture $facture) use ($paiements) {
                $eleve = $facture->eleve;
                $nomEleve = trim(($eleve?->prenom ?? '') . ' ' . ($eleve?->nom ?? ''));
                $nomEleve = $nomEleve !== '' ? $nomEleve : ($eleve?->nom ?? 'Élève');

                $moisFacture = $facture->mois?->locale('fr')->translatedFormat('F Y') ?? '';
                $moisKey = $facture->mois?->format('Y-m') ?? null;

                $remises = [];
                if ($facture->montant_remise > 0) {
                    $remises[] = [
                        'type' => 'montant',
                        'valeur' => (int) round($facture->montant_remise),
                        'commentaire' => 'Remise facture',
                    ];
                }

                $paiementsFacture = collect()
                    ->merge($paiements->get('facture:' . $facture->id, collect()))
                    ->when($moisKey, fn (Collection $collection) => $collection->merge(
                        $paiements->get('eleve:' . $facture->eleve_id . '|mois:' . $moisKey, collect())
                    ))
                    ->unique('id')
                    ->values()
                    ->map(fn (Paiement $paiement) => [
                        'montant' => (int) round($paiement->montant),
                        'date' => $paiement->date_paiement?->toDateString(),
                        'mode' => $paiement->mode_paiement,
                        'reference' => $paiement->reference,
                    ])
                    ->all();

                return [
                    'id' => $facture->id,
                    'eleve_id' => $facture->eleve_id,
                    'eleve' => $nomEleve,
                    'classe' => $eleve?->classe?->nom ?? '—',
                    'classe_id' => $eleve?->classe_id,
                    'periode' => $moisFacture !== '' ? $moisFacture : ($moisKey ?? ''),
                    'mois_sort' => $moisKey,
                    'montant_brut' => (int) round($facture->montant_mensuel),
                    'workflow' => null,
                    'remises' => $remises,
                    'versements' => $paiementsFacture,
                ];
            })
            ->all();
    }
}
