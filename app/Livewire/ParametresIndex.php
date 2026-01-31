<?php

namespace App\Livewire;

use App\Models\AnneeScolaire;
use Illuminate\Support\Str;
use Livewire\Component;

class ParametresIndex extends Component
{
    public string $annee_debut = '';
    public string $annee_fin = '';
    public string $mois_personnalises = '';
    public ?string $coefficient_defaut = null;
    public ?string $trimestre_1_debut = null;
    public ?string $trimestre_1_fin = null;
    public ?string $trimestre_2_debut = null;
    public ?string $trimestre_2_fin = null;
    public ?string $trimestre_3_debut = null;
    public ?string $trimestre_3_fin = null;
    public bool $activer = true;

    protected function rules(): array
    {
        return [
            'annee_debut' => ['required', 'integer', 'digits:4'],
            'annee_fin' => ['required', 'integer', 'digits:4', 'gte:annee_debut'],
            'mois_personnalises' => ['nullable', 'string', 'max:2000'],
            'coefficient_defaut' => ['nullable', 'numeric', 'min:0'],
            'trimestre_1_debut' => ['nullable', 'date'],
            'trimestre_1_fin' => ['nullable', 'date', 'after_or_equal:trimestre_1_debut'],
            'trimestre_2_debut' => ['nullable', 'date'],
            'trimestre_2_fin' => ['nullable', 'date', 'after_or_equal:trimestre_2_debut'],
            'trimestre_3_debut' => ['nullable', 'date'],
            'trimestre_3_fin' => ['nullable', 'date', 'after_or_equal:trimestre_3_debut'],
            'activer' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $mois = $this->normaliserMois($validated['mois_personnalises'] ?? '');

        $annee = AnneeScolaire::create([
            'annee_debut' => (int) $validated['annee_debut'],
            'annee_fin' => (int) $validated['annee_fin'],
            'mois_personnalises' => $mois,
            'coefficient_defaut' => $validated['coefficient_defaut'] ?? null,
            'trimestres' => $this->normaliserTrimestres($validated),
            'est_active' => $validated['activer'],
            'est_cloturee' => false,
        ]);

        if ($validated['activer']) {
            AnneeScolaire::query()
                ->where('id', '!=', $annee->id)
                ->update(['est_active' => false]);
        }

        $this->reset([
            'annee_debut',
            'annee_fin',
            'mois_personnalises',
            'coefficient_defaut',
            'trimestre_1_debut',
            'trimestre_1_fin',
            'trimestre_2_debut',
            'trimestre_2_fin',
            'trimestre_3_debut',
            'trimestre_3_fin',
        ]);
        $this->activer = true;
        session()->flash('status', "L'année scolaire a été ajoutée.");
    }

    public function activer(int $id): void
    {
        $annee = AnneeScolaire::findOrFail($id);
        if ($annee->est_cloturee) {
            session()->flash('status', "Impossible d'activer une année clôturée.");
            return;
        }

        AnneeScolaire::query()->update(['est_active' => false]);
        $annee->update(['est_active' => true]);

        session()->flash('status', "L'année scolaire {$annee->libelle} est maintenant active.");
    }

    public function cloturer(int $id): void
    {
        $annee = AnneeScolaire::findOrFail($id);

        $annee->update([
            'est_active' => false,
            'est_cloturee' => true,
        ]);

        session()->flash('status', "L'année scolaire {$annee->libelle} a été clôturée.");
    }

    private function normaliserMois(?string $valeur): array
    {
        $liste = collect(preg_split('/[\n,]+/', (string) $valeur))
            ->map(fn ($item) => Str::of($item)->trim()->toString())
            ->filter(fn ($item) => $item !== '')
            ->values()
            ->all();

        return $liste;
    }

    private function normaliserTrimestres(array $validated): array
    {
        $trimestres = [
            1 => [
                'debut' => $validated['trimestre_1_debut'] ?? null,
                'fin' => $validated['trimestre_1_fin'] ?? null,
            ],
            2 => [
                'debut' => $validated['trimestre_2_debut'] ?? null,
                'fin' => $validated['trimestre_2_fin'] ?? null,
            ],
            3 => [
                'debut' => $validated['trimestre_3_debut'] ?? null,
                'fin' => $validated['trimestre_3_fin'] ?? null,
            ],
        ];

        return collect($trimestres)
            ->filter(fn ($periode) => filled($periode['debut']) || filled($periode['fin']))
            ->toArray();
    }

    public function render()
    {
        $annees = AnneeScolaire::query()
            ->orderByDesc('annee_debut')
            ->get();

        return view('livewire.parametres-index', [
            'annees' => $annees,
        ])->layout('layouts.app', ['header' => 'Paramètres']);
    }
}
