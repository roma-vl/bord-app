<?php

namespace App\Http\Services;

use App\Models\Adverts\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function getCategories()
    {
        return Category::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft')
            ->get();
    }

    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name'], '-');
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $data['slug'] = Str::slug($data['name'], '-');
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }

    public function moveUp(Category $category): void
    {
        $category->up();
    }

    public function moveDown(Category $category): void
    {
        $category->down();
    }

    public function moveToTop(Category $category): void
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }
    }

    public function moveToBottom(Category $category): void
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }
    }
}
