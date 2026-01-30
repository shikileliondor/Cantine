<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = SeedDataReader::read('users.json');

        foreach ($users as $userData) {
            if (!is_array($userData)) {
                continue;
            }

            $roles = $userData['roles'] ?? [];

            unset($userData['roles']);

            $user = User::query()->create($userData);

            if (!is_array($roles) || $roles === []) {
                continue;
            }

            $roleIds = Role::query()
                ->whereIn('nom', $roles)
                ->pluck('id')
                ->all();

            if ($roleIds !== []) {
                $user->roles()->attach($roleIds);
            }
        }
    }
}
