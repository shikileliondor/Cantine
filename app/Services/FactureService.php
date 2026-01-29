<?php

namespace App\Services;

use App\Models\Facture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FactureService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Facture::query()
            ->with('eleve')
            ->orderByDesc('mois')
            ->paginate($perPage);
    }

    public function create(array $data): Facture
    {
        return Facture::create($data);
    }

    public function update(Facture $facture, array $data): Facture
    {
        $facture->update($data);

        return $facture;
    }

    public function delete(Facture $facture): void
    {
        $facture->delete();
    }
}
