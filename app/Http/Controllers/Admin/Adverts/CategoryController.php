<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft')
            ->get();
        return Inertia::render('Admin/Advert/Index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:advert_categories,name',
            'slug' => 'string|max:255|unique:advert_categories,slug',
            'parent_id' => 'nullable|exists:advert_categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['name'], '-');

        Category::create($validated);

        return redirect()->route('admin.adverts.category.index')->with('success', 'Категорія створена!');
    }
    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft')
            ->get()->toArray();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function edit(Category $category)
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        return response()->json([
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:advert_categories,name,' . $category->id,
            'slug' => 'string|max:255|unique:advert_categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:advert_categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['name'], '-');

        $category->update($validated);

        return redirect()->route('admin.adverts.category.index')->with('success', 'Категорія оновлена!');
    }

    public function show( Category $category)
    {
        $parentAttributes = $category->getParentAttributes();
        $attributes = $category->attributes()->orderBy('sort')->get();
        return Inertia::render('Admin/Advert/Show', compact('category', 'attributes', 'parentAttributes'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.adverts.category.index')->with('success', 'Категорія видалена!');
    }

    public function moveUp(Category $category)
    {
        $category->up();
        return redirect()->route('admin.adverts.category.index');
    }

    public function moveDown(Category $category)
    {
        $category->down();
        return redirect()->route('admin.adverts.category.index');
    }

    public function moveToTop(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }
        return redirect()->route('admin.adverts.category.index');
    }

    public function moveToBottom(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }
        return redirect()->route('admin.adverts.category.index');
    }


}
