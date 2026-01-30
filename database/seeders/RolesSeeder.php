<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = SeedDataReader::read('roles.json');

        foreach ($roles as $roleData) {
            if (!is_array($roleData)) {
                continue;
            }

            Role::query()->create($roleData);
        }
    }
}
