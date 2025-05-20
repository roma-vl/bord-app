<?php

namespace App\Http\Requests\Admin\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class RejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reject_reason' => 'required|string|min:3|max:255',
        ];
    }
}
