<?php

namespace App\Http\Requests\ContactParent;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['required', 'integer', 'exists:eleves,id'],
            'nom' => ['required', 'string', 'max:255'],
            'lien_parental' => ['nullable', 'string', 'in:pere,mere,tuteur,autre'],
            'telephone_principal' => ['required', 'string', 'max:255'],
            'telephone_secondaire' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
