<?php

namespace Database\Seeders;

use App\Models\ContactParent;
use App\Models\Eleve;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ContactsParentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        foreach (Eleve::query()->pluck('id') as $eleveId) {
            ContactParent::query()->create([
                'eleve_id' => $eleveId,
                'nom' => $faker->name,
                'lien_parental' => $faker->randomElement(['pere', 'mere', 'tuteur']),
                'telephone_principal' => $faker->phoneNumber,
                'telephone_secondaire' => $faker->optional()->phoneNumber,
                'email' => $faker->optional()->safeEmail,
            ]);
        }
    }
}
