<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id ?? 'NULL';

        return [
            'key' => 'required|string|unique:permissions,key,' . $permissionId,
            'description' => 'nullable|string',
        ];
    }
}
