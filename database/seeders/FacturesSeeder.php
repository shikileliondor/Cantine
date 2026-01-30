<?php

namespace Database\Seeders;

use App\Models\Facture;
use Illuminate\Database\Seeder;

class FacturesSeeder extends Seeder
{
    public function run(): void
    {
        $factures = SeedDataReader::read('factures.json');

        foreach ($factures as $factureData) {
            if (!is_array($factureData)) {
                continue;
            }

            Facture::query()->create($factureData);
        }
    }
}
