<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'allow_registration' =>  ['required', 'boolean'],
            'require_email_verification' => ['required', 'boolean'],
        ];
    }
}
