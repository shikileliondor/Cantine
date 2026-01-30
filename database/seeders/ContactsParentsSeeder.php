<?php

namespace Database\Seeders;

use App\Models\ContactParent;
use Illuminate\Database\Seeder;

class ContactsParentsSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = SeedDataReader::read('contacts_parents.json');

        foreach ($contacts as $contactData) {
            if (!is_array($contactData)) {
                continue;
            }

            ContactParent::query()->create($contactData);
        }
    }
}
