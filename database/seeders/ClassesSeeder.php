<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        Classe::query()->insert([
            [
                'nom' => 'CP',
                'niveau' => 'Primaire',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'CE1',
                'niveau' => 'Primaire',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'CM2',
                'niveau' => 'Primaire',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
