<?php

namespace Database\Seeders;

use App\Models\Facture;
use App\Models\Paiement;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PaiementsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        foreach (Facture::query()->get() as $facture) {
            Paiement::query()->create([
                'eleve_id' => $facture->eleve_id,
                'facture_id' => $facture->id,
                'mois' => $facture->mois,
                'montant' => $faker->randomElement([100, 150, 200]),
                'date_paiement' => Carbon::now()->subDays(2),
                'mode_paiement' => $faker->randomElement(['especes', 'cheque', 'virement', 'carte']),
                'reference' => Str::upper(Str::random(8)),
            ]);
        }
    }
}
