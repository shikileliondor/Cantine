<?php

namespace App\Livewire;

use Livewire\Component;

class TarifsIndex extends Component
{
    public array $tarifs = [];

    public function mount(): void
    {
        $this->tarifs = [
            [
                'classe' => 'CP1',
                'montant_mensuel' => 25000,
                'debut' => '2024-09',
                'fin' => '2025-07',
            ],
            [
                'classe' => 'CM2',
                'montant_mensuel' => 30000,
                'debut' => '2024-09',
                'fin' => '2025-07',
            ],
            [
                'classe' => 'Collège',
                'montant_mensuel' => 35000,
                'debut' => '2024-09',
                'fin' => '2025-07',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.tarifs-index')->layout('layouts.app', ['header' => 'Tarifs']);
    }
}
