<?php

namespace Database\Seeders;

use App\Models\ParametreCantine;
use Illuminate\Database\Seeder;

class ParametresCantineSeeder extends Seeder
{
    public function run(): void
    {
        $parametres = SeedDataReader::read('parametres_cantine.json');

        foreach ($parametres as $parametreData) {
            if (!is_array($parametreData)) {
                continue;
            }

            ParametreCantine::query()->create($parametreData);
        }
    }
}
