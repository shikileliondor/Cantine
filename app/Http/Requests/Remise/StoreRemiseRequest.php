<?php

namespace App\Http\Requests\Remise;

use Illuminate\Foundation\Http\FormRequest;

class StoreRemiseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['nullable', 'integer', 'exists:eleves,id'],
            'libelle' => ['required', 'string', 'max:255'],
            'type_remise' => ['required', 'string', 'in:fixe,pourcentage'],
            'valeur' => ['required', 'numeric', 'min:0'],
            'actif' => ['nullable', 'boolean'],
        ];
    }
}
