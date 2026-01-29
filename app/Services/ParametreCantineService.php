<?php

namespace App\Services;

use App\Models\ParametreCantine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ParametreCantineService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return ParametreCantine::query()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function create(array $data): ParametreCantine
    {
        return ParametreCantine::create($data);
    }

    public function update(ParametreCantine $parametreCantine, array $data): ParametreCantine
    {
        $parametreCantine->update($data);

        return $parametreCantine;
    }

    public function delete(ParametreCantine $parametreCantine): void
    {
        $parametreCantine->delete();
    }
}
