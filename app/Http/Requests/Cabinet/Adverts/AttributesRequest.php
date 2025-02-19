<?php

namespace App\Http\Requests\Cabinet\Adverts;

use App\Models\Adverts\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributesRequest extends FormRequest
{
    private ?Category $category = null;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $this->loadCategory();

        $items = [];
        if ($this->category) {
            foreach ($this->category->allArrayAttributes() as $attribute) {
                $rules = [
                    $attribute['is_required'] ? 'required' : 'nullable',
                ];
                if ($attribute['type'] === 'integer') {
                    $rules[] = 'integer';
                } elseif ($attribute['type'] === 'float') {
                    $rules[] = 'numeric';
                } else {
                    $rules[] = 'string';
                    $rules[] = 'max:255';
                }
                if (!empty($attribute['variants'])) {
                    $rules[] = Rule::in($attribute['variants']);
                }
                $items['attributes.' . $attribute['id']] = $rules;
            }

        }

        return array_merge([
            'category_id' => ['required', 'exists:advert_categories,id'],
            'title' => 'required|string',
            'content' => 'required|string',
            'price' => 'required|integer',
            'address' => 'required|string',
        ], $items);
    }

    private function loadCategory(): void
    {
        if (!$this->category) {
            $this->category = Category::find($this->input('category_id'));
        }
    }
}
