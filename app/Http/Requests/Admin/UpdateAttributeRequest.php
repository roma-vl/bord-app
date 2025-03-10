<?php

namespace App\Http\Requests\Admin;

use App\Models\Adverts\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'max:255'],
            'is_required' => 'nullable|boolean',
            'variant' => 'nullable|string',
            'sort' => 'required|integer',
        ];
    }
}
