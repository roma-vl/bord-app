<?php

namespace App\Http\Requests\Cabinet\Banners;

use App\Models\Banners\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        [$width, $height] = [0, 0];
        if ($format = $this->input('format')) {
            [$width, $height] = explode('x', $format);
        }
        return [
            'name' => 'required|string',//['required', 'exists:advert_categories,id'],
            'limit' => 'required|integer',
            'url' => 'required|url',
            'format' => ['required', 'string', Rule::in(Banner::formatsList())],
            'file' => 'required|image|mimes:jpeg,jpg,png,gif|dimensions:min_width=' . $width . ',min_height=' . $height,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Введіть назву банера .',
        ];
    }

}
