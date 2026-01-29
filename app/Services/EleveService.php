<?php

namespace App\Services;

use App\Models\Eleve;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EleveService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Eleve::query()
            ->with('classe')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate($perPage);
    }

    public function create(array $data): Eleve
    {
        return Eleve::create($data);
    }

    public function update(Eleve $eleve, array $data): Eleve
    {
        $eleve->update($data);

        return $eleve;
    }

    public function delete(Eleve $eleve): void
    {
        $eleve->delete();
    }
}
