<?php

namespace App\Http\Services;

use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;

class AttributeService
{
    public function create(Category $category, array $data): Attribute
    {
        return $category->attributes()->create($this->processAttributeData($data));
    }

    public function update(Attribute $attribute, array $data): Attribute
    {
        $attribute->update($this->processAttributeData($data));

        return $attribute;
    }

    public function delete(Attribute $attribute): void
    {
        $attribute->delete();
    }

    public function processAttributeData(array $data): array
    {
        $data['is_required'] = (bool) ($data['is_required'] ?? false);

        if (isset($data['variants']) && is_array($data['variants'])) {
            $data['variants'] = array_values(array_filter(array_map('trim', $data['variants'])));
        }

        return $data;
    }
}
