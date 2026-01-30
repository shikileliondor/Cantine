<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\Facture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FacturesSeeder extends Seeder
{
    public function run(): void
    {
        $mois = Carbon::now()->startOfMonth();
        $montantMensuel = 250.00;
        $montantRemise = round($montantMensuel * 0.10, 2);
        $montantTotal = round($montantMensuel - $montantRemise, 2);

        $eleves = Eleve::query()->limit(5)->get();

        foreach ($eleves as $eleve) {
            Facture::query()->create([
                'eleve_id' => $eleve->id,
                'mois' => $mois,
                'montant_mensuel' => $montantMensuel,
                'montant_remise' => $montantRemise,
                'montant_total' => $montantTotal,
                'date_limite' => $mois->copy()->addDays(10),
                'statut' => 'partiel',
            ]);
        }
    }
}
