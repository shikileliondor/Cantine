<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Eleve;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ElevesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $classeIds = Classe::query()->pluck('id')->all();

        if ($classeIds === []) {
            return;
        }

        $eleves = [];

        for ($i = 0; $i < 10; $i++) {
            $eleves[] = [
                'classe_id' => $faker->randomElement($classeIds),
                'prenom' => $faker->firstName,
                'nom' => $faker->lastName,
                'date_naissance' => $faker->dateTimeBetween('-12 years', '-6 years')->format('Y-m-d'),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Eleve::query()->insert($eleves);
    }
}
