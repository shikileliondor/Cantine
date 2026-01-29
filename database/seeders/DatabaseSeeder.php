<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        $roles = [
            ['nom' => 'admin', 'description' => 'Administrateur de la cantine'],
            ['nom' => 'gestionnaire', 'description' => 'Gestionnaire cantine'],
            ['nom' => 'caissier', 'description' => 'Caissier cantine'],
        ];

        DB::table('roles')->insert($roles);

        $admin = User::factory()->create([
            'name' => 'Admin Cantine',
            'email' => 'admin@cantine.test',
            'password' => Hash::make('password'),
        ]);

        $adminRoleId = DB::table('roles')->where('nom', 'admin')->value('id');
        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('parametres_cantine')->insert([
            'montant_mensuel' => 250.00,
            'jour_limite_paiement' => 10,
            'prorata_actif' => true,
            'remises_autorisees' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $classes = [
            ['nom' => 'CP', 'niveau' => 'Primaire', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'CE1', 'niveau' => 'Primaire', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'CM2', 'niveau' => 'Primaire', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('classes')->insert($classes);

        $classeIds = DB::table('classes')->pluck('id')->all();

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
        DB::table('eleves')->insert($eleves);

        $elevesIds = DB::table('eleves')->pluck('id')->all();

        foreach ($elevesIds as $eleveId) {
            DB::table('contacts_parents')->insert([
                'eleve_id' => $eleveId,
                'nom' => $faker->name,
                'lien_parental' => $faker->randomElement(['pere', 'mere', 'tuteur']),
                'telephone_principal' => $faker->phoneNumber,
                'telephone_secondaire' => $faker->optional()->phoneNumber,
                'email' => $faker->optional()->safeEmail,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('notes_eleves')->insert([
                'eleve_id' => $eleveId,
                'type_note' => $faker->randomElement(['allergie', 'regime', 'remarque']),
                'contenu' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('remises')->insert([
            'eleve_id' => null,
            'libelle' => 'Remise fratrie',
            'type_remise' => 'pourcentage',
            'valeur' => 10,
            'actif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (array_slice($elevesIds, 0, 5) as $eleveId) {
            $mois = now()->startOfMonth()->format('Y-m-d');
            $montantMensuel = 250.00;
            $montantRemise = $montantMensuel * 0.10;
            $montantTotal = $montantMensuel - $montantRemise;

            $factureId = DB::table('factures')->insertGetId([
                'eleve_id' => $eleveId,
                'mois' => $mois,
                'montant_mensuel' => $montantMensuel,
                'montant_remise' => $montantRemise,
                'montant_total' => $montantTotal,
                'date_limite' => now()->startOfMonth()->addDays(10)->format('Y-m-d'),
                'statut' => 'partiel',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('paiements')->insert([
                'eleve_id' => $eleveId,
                'facture_id' => $factureId,
                'mois' => $mois,
                'montant' => $faker->randomElement([100, 150, 200]),
                'date_paiement' => now()->subDays(2)->format('Y-m-d'),
                'mode_paiement' => $faker->randomElement(['especes', 'cheque', 'virement', 'carte']),
                'reference' => Str::upper(Str::random(8)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
