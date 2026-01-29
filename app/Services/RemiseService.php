<?php

namespace App\Services;

use App\Models\Remise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RemiseService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Remise::query()
            ->with('eleve')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function create(array $data): Remise
    {
        return Remise::create($data);
    }

    public function update(Remise $remise, array $data): Remise
    {
        $remise->update($data);

        return $remise;
    }

    public function delete(Remise $remise): void
    {
        $remise->delete();
    }
}
