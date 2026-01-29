<?php

namespace App\Http\Requests\Paiement;

use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['required', 'integer', 'exists:eleves,id'],
            'facture_id' => ['nullable', 'integer', 'exists:factures,id'],
            'mois' => ['required', 'date'],
            'montant' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
            'mode_paiement' => ['required', 'string', 'in:especes,cheque,virement,carte,autre'],
            'reference' => ['nullable', 'string', 'max:255'],
        ];
    }
}
