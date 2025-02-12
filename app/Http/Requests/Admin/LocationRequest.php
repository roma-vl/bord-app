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
        $rules = [
            'type' => 'required|in:country,region,area,village',
            'name' => 'required|string|max:150',
        ];

        if ($this->type === 'region') {
            $rules['country_id'] = 'required|integer|exists:located_countrys,id';
        } elseif ($this->type === 'area') {
            $rules['country_id'] = 'required|integer|exists:located_countrys,id';
            $rules['region_id'] = 'required|integer|exists:located_region,id';
        } elseif ($this->type === 'village') {
            $rules['country_id'] = 'required|integer|exists:located_countrys,id';
            $rules['region_id'] = 'required|integer|exists:located_region,id';
            $rules['area_id'] = 'required|integer|exists:located_area,id';
        }

        return $rules;
    }
}
