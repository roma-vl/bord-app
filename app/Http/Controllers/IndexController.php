<?php

namespace App\Http\Controllers;

use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Services\Adverts\AdvertService;
use App\Http\Services\Adverts\SearchService;
use App\Http\Services\CategoryService;
use App\Http\Services\LocaleService;
use App\Http\Services\LocationService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __construct(
        private readonly AdvertService $advertService,
        private readonly CategoryService $categoryService,
        private readonly SearchService $searchService,
        private readonly LocaleService $localeService,
        private readonly LocationService $locationService,
    ) {}

    public function changeLocale(string $locale): RedirectResponse
    {
        $this->localeService->changeLocale($locale);
        return redirect()->back();
    }

    public function index(): Response
    {
        return Inertia::render('Index', [
            'categories' => $this->categoryService->getFirstLevelCategoriesWithChildren(),
            'news' => $this->advertService->getLatest(),
            'vip' => $this->advertService->getVip(),
        ]);
    }

    public function regions(): JsonResponse
    {
        $country = $this->locationService->getCountries()->first();
        $regions = $this->locationService->getRegions($country);

        return response()->json([
            'regions' => $regions,
        ]);
    }

    public function cities(Location $region): JsonResponse
    {
        $cities = $this->locationService->getCities($region);

        return response()->json([
            'cities' => $cities,
        ]);
    }

    public function search($region): JsonResponse
    {
        $regions = $this->locationService->search($region);

        return response()->json([
            'regions' => $regions
        ]);

    }

    public function searchAdvert(SearchRequest $request, ?string $urlPath = ''): Response
    {
        $data = $this->categoryService->parseCategoryAndLocationFromUrl($urlPath);

        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 5);
        if ($perPage < 1) {
            $perPage = 1;
        }
        if ($page < 1) {
            $page = 1;
        }

        $results = $this->searchService->search($data['categories']->last(), $data['locations']->last(), $request, $urlPath, $page, $perPage);

        $locationsCounts = $results->regionsCounts;
        $categoriesCounts = $results->categoriesCounts;

        $locations = $data['locations']->filter(function (Location $location) use ($locationsCounts) {
            return isset($locationsCounts[$location->id]) && $locationsCounts[$location->id] > 0;
        });
        $childCategories = $data['childCategories']
            ->filter(function (Category $category) use ($categoriesCounts) {
                return isset($categoriesCounts[$category->id]) && $categoriesCounts[$category->id] > 0;
            })
            ->values();

        $categories = $data['categories']->filter(function (Category $category) use ($categoriesCounts) {
            return isset($categoriesCounts[$category->id]) && $categoriesCounts[$category->id] > 0;
        });

        //        $categoryTree = $this->categoryService->getFirstLevelCategoriesWithChildren();
        $attributes = count($data['categories']) ? $data['categories']->last()->allArrayAttributes() : [];
        $categoryTree = $this->categoryService->getFirstLevelCategoriesWithChildren();

        $categoryFilters = $categoryTree->map(fn ($category) => $this->formatCategoryWithAttributes($category));

        return Inertia::render('Search/List', [
            'adverts' => [
                'data' => $results->adverts->items(),
                'total' => $results->adverts->total(),
                'page' => $results->adverts->currentPage(),
                'per_page' => $results->adverts->perPage(),
                'last_page' => $results->adverts->lastPage(),
                'links' => $results->adverts->linkCollection(),
            ],
            'locations' => $locations,
            'categories' => $categories,
            'childCategories' => $childCategories,
            'attributes' => $attributes,
            'regionsCounts' => $results->regionsCounts,
            'categoriesCounts' => $results->categoriesCounts,
            'categoryFilters' => $categoryFilters,
            'query' => $request->query(),
        ]);
    }

    private function formatCategoryWithAttributes(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'attributes' => $category->allArrayAttributes(), // або інший метод
            'children' => $category->children->map(fn ($child) => $this->formatCategoryWithAttributes($child))->values(),
        ];
    }

    public function show(Advert $advert)
    {
        $advert->load(['category.ancestors', 'value.attribute',
            'photo', 'user', 'region', 'favorites']);
        $values = $advert->value->map(function ($value) {
            return [
                'attribute' => $value->attribute->name ?? null,
                'value' => $value->value,
            ];
        });

        $isFavorited = $advert->favorites->contains(Auth::id());

        return Inertia::render('Advert/Show', [
            'advert' => $advert,
            'category' => $advert->category,
            'values' => $values,
            'user' => $advert->values,
            'region' => $advert->region,
            'photos' => $advert->photo,
            'isFavorited' => $isFavorited,
        ]);
    }

    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }
}
