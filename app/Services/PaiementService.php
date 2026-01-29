<?php

namespace App\Services;

use App\Models\Paiement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaiementService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Paiement::query()
            ->with(['eleve', 'facture'])
            ->orderByDesc('date_paiement')
            ->paginate($perPage);
    }

    public function create(array $data): Paiement
    {
        return Paiement::create($data);
    }

    public function update(Paiement $paiement, array $data): Paiement
    {
        $paiement->update($data);

        return $paiement;
    }

    public function delete(Paiement $paiement): void
    {
        $paiement->delete();
    }
}
