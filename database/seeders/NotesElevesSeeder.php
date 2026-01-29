<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\NoteEleve;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class NotesElevesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        foreach (Eleve::query()->pluck('id') as $eleveId) {
            NoteEleve::query()->create([
                'eleve_id' => $eleveId,
                'type_note' => $faker->randomElement(['allergie', 'regime', 'remarque']),
                'contenu' => $faker->sentence,
            ]);
        }
    }
}
