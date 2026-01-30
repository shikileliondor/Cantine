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

        $eleves = [];

        for ($i = 0; $i < 10; $i++) {
            $classeId = null;

            if ($classeIds !== []) {
                $classeId = $faker->optional(0.85)->randomElement($classeIds);
            }

            $eleves[] = [
                'classe_id' => $classeId,
                'prenom' => $faker->firstName,
                'nom' => $faker->lastName,
                'date_naissance' => $faker->optional()->dateTimeBetween('-12 years', '-6 years')->format('Y-m-d'),
                'statut' => $faker->randomElement(['actif', 'inactif']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Eleve::query()->insert($eleves);
    }
}
