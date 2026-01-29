<?php

namespace App\Services;

use App\Models\Classe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClasseService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Classe::query()
            ->orderBy('nom')
            ->paginate($perPage);
    }

    public function create(array $data): Classe
    {
        return Classe::create($data);
    }

    public function update(Classe $classe, array $data): Classe
    {
        $classe->update($data);

        return $classe;
    }

    public function delete(Classe $classe): void
    {
        $classe->delete();
    }
}
