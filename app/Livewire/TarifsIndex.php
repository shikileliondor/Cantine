<?php

namespace App\Livewire;

use Livewire\Component;

class TarifsIndex extends Component
{
    public array $tarifs = [];
    public string $classe = '';
    public string $montant = '';
    public string $debut = '';
    public string $fin = '';

    public function ajouterTarif(): void
    {
        $donnees = $this->validate([
            'classe' => ['required', 'string', 'max:120'],
            'montant' => ['required', 'numeric', 'min:0'],
            'debut' => ['required', 'date_format:Y-m'],
            'fin' => ['required', 'date_format:Y-m', 'after_or_equal:debut'],
        ]);

        $this->tarifs = [
            [
                'classe' => trim($donnees['classe']),
                'montant_mensuel' => (int) $donnees['montant'],
                'debut' => $donnees['debut'],
                'fin' => $donnees['fin'],
            ],
            ...$this->tarifs,
        ];

        $this->reset(['classe', 'montant', 'debut', 'fin']);
    }

    public function render()
    {
        return view('livewire.tarifs-index')->layout('layouts.app', ['header' => 'Tarifs']);
    }
}
