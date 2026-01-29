<?php

namespace App\Services;

use App\Models\NoteEleve;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NoteEleveService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return NoteEleve::query()
            ->with('eleve')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function create(array $data): NoteEleve
    {
        return NoteEleve::create($data);
    }

    public function update(NoteEleve $noteEleve, array $data): NoteEleve
    {
        $noteEleve->update($data);

        return $noteEleve;
    }

    public function delete(NoteEleve $noteEleve): void
    {
        $noteEleve->delete();
    }
}
