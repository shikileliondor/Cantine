<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use Illuminate\Database\Seeder;

class AnneesScolairesSeeder extends Seeder
{
    public function run(): void
    {
        $annees = SeedDataReader::read('annees_scolaires.json');

        foreach ($annees as $anneeData) {
            if (!is_array($anneeData)) {
                continue;
            }

            AnneeScolaire::query()->create($anneeData);
        }
    }
}
