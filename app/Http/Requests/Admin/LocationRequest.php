<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return[
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:locations,id',
        ];
    }
}
