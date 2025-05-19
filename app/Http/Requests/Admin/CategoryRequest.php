<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:advert_categories,name,'.$this->category?->id,
            'slug' => 'string|max:255|unique:advert_categories,slug,'.$this->category?->id,
            'parent_id' => 'nullable|exists:advert_categories,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
