<?php

namespace App\Http\Requests\Remise;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRemiseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['nullable', 'integer', 'exists:eleves,id'],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'type_remise' => ['sometimes', 'string', 'in:fixe,pourcentage'],
            'valeur' => ['sometimes', 'numeric', 'min:0'],
            'actif' => ['nullable', 'boolean'],
        ];
    }
}
