<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TarifsIndex extends Component
{
    public array $tarifs = [];
    public string $classe = '';
    public string $montant = '';
    public string $debut_mois = '';
    public string $debut_annee = '';
    public string $fin_mois = '';
    public string $fin_annee = '';
    public array $moisOptions = [];
    public array $anneesOptions = [];

    public function mount(): void
    {
        $anneeCourante = (int) now()->format('Y');

        $this->moisOptions = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre',
        ];
        $this->anneesOptions = range($anneeCourante - 1, $anneeCourante + 5);
        $this->tarifs = session('tarifs', []);
    }

    public function ajouterTarif(): void
    {
        $donnees = $this->validate([
            'classe' => ['required', 'string', 'max:120'],
            'montant' => ['required', 'numeric', 'min:0'],
            'debut_mois' => ['required', Rule::in(array_keys($this->moisOptions))],
            'debut_annee' => ['required', 'integer', Rule::in($this->anneesOptions)],
            'fin_mois' => ['required', Rule::in(array_keys($this->moisOptions))],
            'fin_annee' => ['required', 'integer', Rule::in($this->anneesOptions)],
        ]);

        $debut = sprintf('%04d-%02d', $donnees['debut_annee'], $donnees['debut_mois']);
        $fin = sprintf('%04d-%02d', $donnees['fin_annee'], $donnees['fin_mois']);
        $debutDate = Carbon::createFromFormat('Y-m', $debut);
        $finDate = Carbon::createFromFormat('Y-m', $fin);

        if ($finDate->lt($debutDate)) {
            $this->addError('fin_mois', 'La fin doit être postérieure ou égale au début.');

            return;
        }

        $this->tarifs = [
            [
                'classe' => trim($donnees['classe']),
                'montant_mensuel' => (int) $donnees['montant'],
                'debut' => $debut,
                'fin' => $fin,
            ],
            ...$this->tarifs,
        ];

        session()->put('tarifs', $this->tarifs);

        $this->reset(['classe', 'montant', 'debut_mois', 'debut_annee', 'fin_mois', 'fin_annee']);
    }

    public function render()
    {
        return view('livewire.tarifs-index')->layout('layouts.app', ['header' => 'Tarifs']);
    }
}
