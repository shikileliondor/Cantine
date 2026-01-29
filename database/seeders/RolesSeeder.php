<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->insert([
            [
                'nom' => 'admin',
                'description' => 'Administrateur de la cantine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'gestionnaire',
                'description' => 'Gestionnaire cantine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'caissier',
                'description' => 'Caissier cantine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
