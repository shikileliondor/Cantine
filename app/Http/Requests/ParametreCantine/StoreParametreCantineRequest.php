<?php

namespace App\Http\Requests\ParametreCantine;

use Illuminate\Foundation\Http\FormRequest;

class StoreParametreCantineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant_mensuel' => ['required', 'numeric', 'min:0'],
            'jour_limite_paiement' => ['required', 'integer', 'between:1,31'],
            'prorata_actif' => ['nullable', 'boolean'],
            'remises_autorisees' => ['nullable', 'boolean'],
        ];
    }
}
