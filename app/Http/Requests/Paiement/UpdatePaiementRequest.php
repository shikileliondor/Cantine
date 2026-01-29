<?php

namespace App\Http\Requests\Paiement;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['sometimes', 'integer', 'exists:eleves,id'],
            'facture_id' => ['nullable', 'integer', 'exists:factures,id'],
            'mois' => ['sometimes', 'date'],
            'montant' => ['sometimes', 'numeric', 'min:0'],
            'date_paiement' => ['sometimes', 'date'],
            'mode_paiement' => ['sometimes', 'string', 'in:especes,cheque,virement,carte,autre'],
            'reference' => ['nullable', 'string', 'max:255'],
        ];
    }
}
