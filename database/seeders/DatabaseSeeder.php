<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            ParametresCantineSeeder::class,
            AnneesScolairesSeeder::class,
            ClassesSeeder::class,
            ElevesSeeder::class,
            ContactsParentsSeeder::class,
            NotesElevesSeeder::class,
            RemisesSeeder::class,
            TarifsSeeder::class,
            FacturesSeeder::class,
            PaiementsSeeder::class,
        ]);
    }
}
