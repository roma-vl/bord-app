<?php

namespace App\Http\Requests\Admin;

use App\Models\Adverts\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttributeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'max:255', Rule::in(Attribute::typesList())],
            'is_required' => 'nullable|boolean',
            'variant' => 'nullable|string',
            'sort' => 'required|integer',
        ];
    }
}
