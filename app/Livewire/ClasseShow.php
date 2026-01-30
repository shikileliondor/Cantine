<?php

namespace App\Livewire;

use App\Models\Classe;
use Livewire\Component;

class ClasseShow extends Component
{
    public Classe $classe;

    public function mount(Classe $classe): void
    {
        $this->classe = $classe->load(['eleves.latestFacture']);
    }

    public function getStatsProperty(): array
    {
        $payes = 0;
        $impayes = 0;

        foreach ($this->classe->eleves as $eleve) {
            $statut = $eleve->latestFacture?->statut;
            if (! $statut) {
                $payes++;
                continue;
            }

            $normalized = mb_strtolower($statut);
            if (str_contains($normalized, 'retard') || str_contains($normalized, 'impay')) {
                $impayes++;
                continue;
            }

            if (str_contains($normalized, 'partiel')) {
                $impayes++;
                continue;
            }

            $payes++;
        }

        return [
            'payes' => $payes,
            'impayes' => $impayes,
        ];
    }

    public function render()
    {
        return view('livewire.classe-show')
            ->layout('layouts.app', ['header' => 'Détail classe']);
    }
}
