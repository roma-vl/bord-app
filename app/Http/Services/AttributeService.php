<?php

namespace App\Http\Services;


use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use Illuminate\Support\Arr;

class AttributeService
{
    public function create(Category $category, array $data): Attribute
    {
        $data['is_required'] = (bool) ($data['is_required'] ?? false);
        $data['variants'] = $this->processVariant($data['variants'] ?? '');

        return $category->attributes()->create($data);
    }

    public function update(Attribute $attribute, array $data): Attribute
    {
        $data['is_required'] = (bool) ($data['is_required'] ?? false);
        $data['variants'] = $this->processVariant($data['variants'] ?? '');

        $attribute->update($data);
        return $attribute;
    }

    public function delete(Attribute $attribute): void
    {
        $attribute->delete();
    }

    private function processVariant(string $variant): array
    {
        return array_filter(array_map('trim', preg_split('#[\r\n]+#', $variant)));
    }
}
