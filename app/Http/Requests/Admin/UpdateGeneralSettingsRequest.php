<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'maintenance_mode' => ['required', 'boolean'],
        ];
    }
}
