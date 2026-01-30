<?php

namespace Database\Seeders;

use App\Models\Paiement;
use Illuminate\Database\Seeder;

class PaiementsSeeder extends Seeder
{
    public function run(): void
    {
        $paiements = SeedDataReader::read('paiements.json');

        foreach ($paiements as $paiementData) {
            if (!is_array($paiementData)) {
                continue;
            }

            Paiement::query()->create($paiementData);
        }
    }
}
