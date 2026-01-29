<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Cantine',
            'email' => 'admin@cantine.test',
            'password' => Hash::make('password'),
        ]);

        $adminRole = Role::query()->where('nom', 'admin')->first();

        if ($adminRole) {
            $admin->roles()->attach($adminRole);
        }
    }
}
