<?php

namespace App\Http\Requests\Banner;

use App\Models\Banners\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'nullable|exists:advert_categories,id',
            'region' => 'nullable|exists:locations,id',
            'format' => ['required', 'string', Rule::in(Banner::formatsList())],
        ];
    }

}
