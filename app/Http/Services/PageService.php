<?php

namespace App\Http\Services;

use App\Models\Adverts\Category;
use App\Models\Location;
use App\Models\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageService
{
    public function getFirstLevelCategoriesWithChildren(): Collection
    {
        $firstLevel = collect();
        $rootCategories = Page::whereIsRoot()->get();
        foreach ($rootCategories as $root) {
            $firstLevel = $root->children()->get();
        }

        return $firstLevel;
    }

    public function parsePageFromUrl(?string $urlPath)
    {
        Cache::flush();
        $cacheKey = 'showPages_' . md5($urlPath);

        return Cache::tags([Page::class])->rememberForever($cacheKey, function () use ($urlPath) {
            $slugs = $urlPath ? array_reverse(explode('/', $urlPath)) : [];
            $locations = collect();

            foreach ($slugs as $slug) {
                if ($location = Page::where('slug', $slug)->first()) {
                    $locations->push($location);
                }
            }

            if (1 > count($locations)) abort(404);

            return $locations;
        });
    }


    public function getPages()
    {
        return Page::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft')
            ->get();
    }

    public function updatePage(Page $page, array $data): Page
    {
        $data['slug'] = Str::slug($data['name'], '-');
        $page->update($data);
        return $page;
    }

    public function deletePage(Page $page): void
    {
        $page->delete();
    }

    public function up(Page $page): void
    {
        $page->up();
    }

    public function down(Page $page): void
    {
        $page->down();
    }

    public function top(Page $page): void
    {
        if ($first = $page->siblings()->defaultOrder()->first()) {
            $page->insertBeforeNode($first);
        }
    }

    public function bottom(Page $page): void
    {
        if ($last = $page->siblings()->defaultOrder('desc')->first()) {
            $page->insertAfterNode($last);
        }
    }
}
