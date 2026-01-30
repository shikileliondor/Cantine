<?php

namespace App\Livewire;

use App\Models\Tarif;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TarifsIndex extends Component
{
    public $tarifs = [];
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
        $this->tarifs = Tarif::orderByDesc('debut_periode')->get();
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

        $debutDate = Carbon::createFromDate((int) $donnees['debut_annee'], (int) $donnees['debut_mois'], 1)->startOfMonth();
        $finDate = Carbon::createFromDate((int) $donnees['fin_annee'], (int) $donnees['fin_mois'], 1)->endOfMonth();

        if ($finDate->lt($debutDate)) {
            $this->addError('fin_mois', 'La fin doit être postérieure ou égale au début.');

            return;
        }

        Tarif::create([
            'classe' => trim($donnees['classe']),
            'montant_mensuel' => $donnees['montant'],
            'debut_periode' => $debutDate,
            'fin_periode' => $finDate,
            'actif' => true,
        ]);

        $this->tarifs = Tarif::orderByDesc('debut_periode')->get();

        $this->reset(['classe', 'montant', 'debut_mois', 'debut_annee', 'fin_mois', 'fin_annee']);
    }

    public function render()
    {
        return view('livewire.tarifs-index')->layout('layouts.app', ['header' => 'Tarifs']);
    }
}
