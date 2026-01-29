<?php

namespace Database\Seeders;

use App\Models\ParametreCantine;
use Illuminate\Database\Seeder;

class ParametresCantineSeeder extends Seeder
{
    public function run(): void
    {
        ParametreCantine::query()->create([
            'montant_mensuel' => 250.00,
            'jour_limite_paiement' => 10,
            'prorata_actif' => true,
            'remises_autorisees' => true,
        ]);
    }
}
