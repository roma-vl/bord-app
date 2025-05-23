<?php

namespace App\Http\Services;

use App\Models\Adverts\Category;
use App\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryService
{
    public function getFirstLevelCategoriesWithChildren(): Collection
    {
        $firstLevel = collect();
        $rootCategories = Category::whereIsRoot()->get();
        foreach ($rootCategories as $root) {
            $firstLevel = $root->children()->get();
        }

        return $firstLevel;
    }

    public function parseCategoryAndLocationFromUrl(?string $urlPath): array
    {
        Cache::flush();
        $cacheKey = 'showCategory_'.md5($urlPath);

        return Cache::tags([Location::class, Category::class])->rememberForever($cacheKey, function () use ($urlPath) {
            $slugs = $urlPath ? array_reverse(explode('/', $urlPath)) : [];

            $locations = collect();
            $categorySlugs = [];

            foreach ($slugs as $slug) {
                if ($location = Location::where('slug', $slug)->first()) {
                    $locations->push($location);
                } else {
                    $categorySlugs[] = $slug;
                }
            }

            $currentLocation = $locations->first();
            $parentLocations = $currentLocation?->ancestors()->get(['id', 'name', 'slug'])->slice(0, -1) ?? collect();
            $locations = $parentLocations->prepend($currentLocation)->reverse()->filter()->values();

            $categories = collect();
            foreach (array_reverse($categorySlugs) as $slug) {
                $category = Category::where('slug', $slug)->first();
                if (! $category) {
                    abort(404);
                }
                $categories->push($category);
            }

            $currentCategory = $categories->last();
            $childCategories = $currentCategory?->descendants()->orderBy('name')->get(['id', 'name', 'slug']) ?? collect();

            return compact('locations', 'categories', 'childCategories');
        });
    }

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
