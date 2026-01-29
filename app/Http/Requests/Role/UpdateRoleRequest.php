<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role = $this->route('role');
        $roleId = $role?->id;

        return [
            'nom' => ['sometimes', 'string', 'max:255', Rule::unique('roles', 'nom')->ignore($roleId)],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
