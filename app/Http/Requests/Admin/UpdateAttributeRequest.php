<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'max:255'],
            'is_required' => 'nullable|boolean',
            'variants' => ['nullable', 'array'],
            'variants.*' => ['required', 'string', 'max:255'],
            'sort' => 'required|integer',
        ];
    }
}
