<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Services\CategoryService;
use App\Models\Adverts\Category;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index()
    {
        return Inertia::render('Admin/Advert/Index', [
            'categories' => $this->categoryService->getCategories()
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory($request->validated());
        return redirect()->route('admin.adverts.category.index')->with('success', 'Категорія створена!');
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'categories' => $this->categoryService->getCategories()
        ]);
    }

    public function edit(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category,
            'categories' => Category::defaultOrder()->withDepth()->get()
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->updateCategory($category, $request->validated());
        return redirect()->route('admin.adverts.category.index')->with('success', 'Категорія оновлена!');
    }

    public function show( Category $category)
    {
        $parentAttributes = $category->getParentAttributes();
        $attributes = $category->attributes()->orderBy('sort')->get();
        return Inertia::render('Admin/Advert/Show', compact('category', 'attributes', 'parentAttributes'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->deleteCategory($category);
        return redirect()->route('admin.adverts.category.index')->with('success', 'Категорія видалена!');
    }

    public function moveUp(Category $category): RedirectResponse
    {
        $this->categoryService->moveUp($category);
        return redirect()->route('admin.adverts.category.index');
    }

    public function moveDown(Category $category): RedirectResponse
    {
        $this->categoryService->moveDown($category);
        return redirect()->route('admin.adverts.category.index');
    }

    public function moveToTop(Category $category): RedirectResponse
    {
        $this->categoryService->moveToTop($category);
        return redirect()->route('admin.adverts.category.index');
    }

    public function moveToBottom(Category $category): RedirectResponse
    {
        $this->categoryService->moveToBottom($category);
        return redirect()->route('admin.adverts.category.index');
    }
}
