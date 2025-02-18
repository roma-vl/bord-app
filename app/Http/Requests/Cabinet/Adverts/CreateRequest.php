<?php

namespace App\Http\Requests\Cabinet\Adverts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Adverts\Category;
class CreateRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            'category_id.required' => 'Оберіть категорію оголошення.',
            'category_id.exists' => 'Обрана категорія не існує.',
            'title.required' => 'Поле "Назва" є обов’язковим.',
            'title.string' => 'Поле "Назва" повинно містити текст.',
            'content.required' => 'Поле "Опис" є обов’язковим.',
            'content.string' => 'Поле "Опис" повинно містити текст.',
            'price.required' => 'Поле "Ціна" є обов’язковим.',
            'price.integer' => 'Поле "Ціна" повинно бути цілим числом.',
            'address.required' => 'Поле "Адреса" є обов’язковим.',
            'address.string' => 'Поле "Адреса" повинно містити текст.',
        ];
    }

    private function loadCategory(): void
    {
        if (!$this->category) {
            $this->category = Category::find($this->input('category_id'));
        }
    }
}
