<?php

namespace App\Http\Requests\ParametreCantine;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParametreCantineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant_mensuel' => ['sometimes', 'numeric', 'min:0'],
            'jour_limite_paiement' => ['sometimes', 'integer', 'between:1,31'],
            'prorata_actif' => ['nullable', 'boolean'],
            'remises_autorisees' => ['nullable', 'boolean'],
        ];
    }
}
