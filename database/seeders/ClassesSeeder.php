<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SeedDataReader::read('classes.json');

        foreach ($classes as $classeData) {
            if (!is_array($classeData)) {
                continue;
            }

            Classe::query()->create($classeData);
        }
    }
}
