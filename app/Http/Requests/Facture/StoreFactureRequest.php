<?php

namespace App\Http\Requests\Facture;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['required', 'integer', 'exists:eleves,id'],
            'mois' => ['required', 'date'],
            'montant_mensuel' => ['required', 'numeric', 'min:0'],
            'montant_remise' => ['nullable', 'numeric', 'min:0'],
            'montant_total' => ['required', 'numeric', 'min:0'],
            'date_limite' => ['nullable', 'date'],
            'statut' => ['nullable', 'string', 'in:a_jour,partiel,retard,non_concerne'],
        ];
    }
}
