<?php

namespace Database\Seeders;

use App\Models\Tarif;
use Illuminate\Database\Seeder;

class TarifsSeeder extends Seeder
{
    public function run(): void
    {
        $tarifs = SeedDataReader::read('tarifs.json');

        foreach ($tarifs as $tarifData) {
            if (!is_array($tarifData)) {
                continue;
            }

            Tarif::query()->create($tarifData);
        }
    }
}
