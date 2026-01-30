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
            $montant = $faker->randomFloat(2, 50, (float) $facture->montant_total);
            $datePaiement = Carbon::instance($facture->mois)->addDays($faker->numberBetween(1, 15));

            Paiement::query()->create([
                'eleve_id' => $facture->eleve_id,
                'facture_id' => $facture->id,
                'mois' => $facture->mois,
                'montant' => $montant,
                'date_paiement' => $datePaiement,
                'mode_paiement' => $faker->randomElement(['especes', 'cheque', 'virement', 'carte']),
                'reference' => Str::upper(Str::random(8)),
            ]);
        }
    }
}
