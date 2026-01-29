<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\Remise;
use Illuminate\Database\Seeder;

class RemisesSeeder extends Seeder
{
    public function run(): void
    {
        Remise::query()->create([
            'eleve_id' => null,
            'libelle' => 'Remise fratrie',
            'type_remise' => 'pourcentage',
            'valeur' => 10,
            'actif' => true,
        ]);

        $eleves = Eleve::query()->limit(3)->get();

        foreach ($eleves as $eleve) {
            Remise::query()->create([
                'eleve_id' => $eleve->id,
                'libelle' => 'Remise sociale',
                'type_remise' => 'fixe',
                'valeur' => 25,
                'actif' => true,
            ]);
        }
    }
}
