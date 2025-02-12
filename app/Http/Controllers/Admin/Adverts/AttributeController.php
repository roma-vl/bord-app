<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\UpdateAttributeRequest;
use App\Http\Services\AttributeService;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AttributeController extends Controller
{
    private AttributeService $service;

    public function __construct(AttributeService $service)
    {
        $this->service = $service;
    }

    public function create(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category,
            'types' => Attribute::typesList(),
        ]);
    }

    public function store(StoreAttributeRequest $request, Category $category): RedirectResponse
    {
        $this->service->create($category, $request->validated());
        return redirect()->route('admin.adverts.category.show', $category->id);
    }

    public function edit(Category $category, Attribute $attribute): JsonResponse
    {
        return response()->json([
            'category' => $category,
            'attribute' => $attribute,
            'types' => Attribute::typesList(),
        ]);
    }

    public function update(UpdateAttributeRequest $request, Category $category, Attribute $attribute): RedirectResponse
    {
        $this->service->update($attribute, $request->validated());
        return redirect()->route('admin.adverts.category.show', $category->id);
    }

    public function destroy(Category $category, Attribute $attribute): RedirectResponse
    {
        $this->service->delete($attribute);
        return redirect()->route('admin.adverts.category.show', $category->id);
    }
}
