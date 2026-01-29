<?php

namespace App\Http\Requests\Eleve;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'classe_id' => ['nullable', 'integer', 'exists:classes,id'],
            'prenom' => ['sometimes', 'string', 'max:255'],
            'nom' => ['sometimes', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date'],
            'statut' => ['nullable', 'string', 'in:actif,inactif'],
        ];
    }
}
