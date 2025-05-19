<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('role');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('roles', 'name')->ignore($this->role)],
            'is_enabled' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }
}
