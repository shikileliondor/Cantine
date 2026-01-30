<?php

namespace Database\Seeders;

use App\Models\Remise;
use Illuminate\Database\Seeder;

class RemisesSeeder extends Seeder
{
    public function run(): void
    {
        $remises = SeedDataReader::read('remises.json');

        foreach ($remises as $remiseData) {
            if (!is_array($remiseData)) {
                continue;
            }

            Remise::query()->create($remiseData);
        }
    }
}
