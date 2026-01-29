<?php

namespace App\Http\Requests\NoteEleve;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['sometimes', 'integer', 'exists:eleves,id'],
            'type_note' => ['sometimes', 'string', 'in:allergie,regime,remarque'],
            'contenu' => ['sometimes', 'string'],
        ];
    }
}
