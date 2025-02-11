<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Admin\Controller;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function create(Category $category)
    {
        return response()->json([
            'category' => $category,
            'types' => Attribute::typesList(),
        ]);

    }

    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255|'. Rule::in(Attribute::typesList()),
            'is_required' => 'nullable|boolean',
            'variant' => 'nullable|string',
            'sort' => 'required|integer'
        ]);

        $category->attributes()->create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_required' => $validated['is_required'],
            'variant' => array_map('trim', preg_split('#[\r\n]+#', $validated['variant'])),
            'sort' => $validated['sort']
        ]);
        return redirect()->route('admin.adverts.category.show', $category->id);
    }

    public function edit( Category $category, Attribute $attribute)
    {
        return response()->json([
            'category' => $category,
            'attribute' => [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'type' => $attribute->type,
                'is_required' => $attribute->is_required,
                'variant' => is_array($attribute->variant)
                    ? implode("\n", $attribute->variant)
                    : $attribute->variant, // Конвертуємо масив назад у рядок
                'sort' => $attribute->sort,
            ],
            'types' => Attribute::typesList(),
        ]);
    }
    public function update(Request $request, Category $category, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255|'. Rule::in(Attribute::typesList()),
            'is_required' => 'nullable|boolean',
            'variant' => 'nullable|string',
            'sort' => 'required|integer'
        ]);


        $attribute = $category->attributes()->where('id', $attribute->id)->firstOrFail();

        $attribute->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_required' => (bool) $validated['is_required'],
            'variant' => array_map('trim', preg_split('#[\r\n]+#', $validated['variant'])),
            'sort' => $validated['sort']
        ]);

        return redirect()->route('admin.adverts.category.show', $category->id);
    }

    public function destroy( Category $category, Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.adverts.category.show', $category->id);

    }

}
