<?php

namespace App\Services;

use App\Models\ContactParent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactParentService
{
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return ContactParent::query()
            ->with('eleve')
            ->orderBy('nom')
            ->paginate($perPage);
    }

    public function create(array $data): ContactParent
    {
        return ContactParent::create($data);
    }

    public function update(ContactParent $contactParent, array $data): ContactParent
    {
        $contactParent->update($data);

        return $contactParent;
    }

    public function delete(ContactParent $contactParent): void
    {
        $contactParent->delete();
    }
}
