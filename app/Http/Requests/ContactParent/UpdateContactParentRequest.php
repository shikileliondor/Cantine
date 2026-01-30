<?php

namespace App\Http\Requests\ContactParent;

use App\Models\ContactParent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['sometimes', 'integer', 'exists:eleves,id'],
            'nom' => ['sometimes', 'string', 'max:255'],
            'lien_parental' => ['nullable', 'string', Rule::in(ContactParent::LIEN_PARENTAL_INPUTS)],
            'telephone_principal' => ['sometimes', 'string', 'max:255'],
            'telephone_secondaire' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
