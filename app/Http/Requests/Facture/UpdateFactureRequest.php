<?php

namespace App\Http\Requests\Facture;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFactureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['sometimes', 'integer', 'exists:eleves,id'],
            'mois' => ['sometimes', 'date'],
            'montant_mensuel' => ['sometimes', 'numeric', 'min:0'],
            'montant_remise' => ['nullable', 'numeric', 'min:0'],
            'montant_total' => ['sometimes', 'numeric', 'min:0'],
            'date_limite' => ['nullable', 'date'],
            'statut' => ['nullable', 'string', 'in:a_jour,partiel,retard,non_concerne'],
        ];
    }
}
