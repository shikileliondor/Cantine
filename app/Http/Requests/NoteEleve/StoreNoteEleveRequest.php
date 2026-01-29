<?php

namespace App\Http\Requests\NoteEleve;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eleve_id' => ['required', 'integer', 'exists:eleves,id'],
            'type_note' => ['required', 'string', 'in:allergie,regime,remarque'],
            'contenu' => ['required', 'string'],
        ];
    }
}
