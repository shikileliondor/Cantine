<?php

namespace Database\Seeders;

use App\Models\NoteEleve;
use Illuminate\Database\Seeder;

class NotesElevesSeeder extends Seeder
{
    public function run(): void
    {
        $notes = SeedDataReader::read('notes_eleves.json');

        foreach ($notes as $noteData) {
            if (!is_array($noteData)) {
                continue;
            }

            NoteEleve::query()->create($noteData);
        }
    }
}
