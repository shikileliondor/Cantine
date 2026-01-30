<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Database\Seeder;

class ElevesSeeder extends Seeder
{
    public function run(): void
    {
        $classeIds = Classe::query()->pluck('id')->all();

        if ($classeIds === []) {
            return;
        }

        $eleves = SeedDataReader::read('eleves.json');

        foreach ($eleves as $eleveData) {
            if (!is_array($eleveData)) {
                continue;
            }

            if (!isset($eleveData['classe_id'])) {
                $eleveData['classe_id'] = $classeIds[0];
            }

            Eleve::query()->create($eleveData);
        }
    }
}
