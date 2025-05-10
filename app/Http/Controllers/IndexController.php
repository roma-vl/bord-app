<?php

namespace App\Http\Controllers;

use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Services\Adverts\AdvertService;
use App\Http\Services\Adverts\SearchService;
use App\Http\Services\CategoryService;
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
    ) {}
    public function changeLocale(string $locale): RedirectResponse
    {
        if (!in_array($locale, ['en', 'uk'])) {
            abort(400);
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        session()->flash('success', trans('auth.language'));

        return redirect()->back();
    }

    public function index(): Response
    {
        return Inertia::render('Index', [
            'categories' => $this->categoryService->getRootCategoriesWithChildren(),
            'news' => $this->advertService->getLatest(),
            'vip' => $this->advertService->getVip()
        ]);
    }


    public function regions(): JsonResponse
    {
        $regions = Location::whereDepth(1)->get();
        return response()->json([
            'regions' => $regions
        ]);
    }
    public function cities(Location $region): JsonResponse
    {
        $cities = $region->descendants()
            ->whereDepth(3)
            ->orderBy('name')
            ->take(300)
            ->get(['name', 'slug', 'id']);

        return response()->json([
            'cities' => $cities
        ]);
    }
    public function search($region): JsonResponse
    {
        if (strlen($region) < 2) {
            return response()->json(['regions' => []]);
        }

        $regions = Location::where('name', 'like', "%{$region}%")
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get(['id', 'name', 'slug']);

        return response()->json(['regions' => $regions]);

    }

    public function searchAdvert(SearchRequest $request): Response
    {
        $urlPath = 'elektronika/kompiuteri-ta-komplektuiuci/serveri';

        $data = $this->categoryService->parseCategoryAndLocationFromUrl($urlPath);

        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 3);

        $results = $this->searchService->search($data['categories']->last(), $data['locations']->last(), $request, $page, $perPage);

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

        $attributes = $data['categories']->last()->allArrayAttributes();
        return Inertia::render('Search/Results', [
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
            'query' => $request->query(),
        ]);
    }

    public function show(Advert $advert)
    {
        $advert->load(['category.ancestors', 'value.attribute',
            'photo', 'user', 'region','favorites']);
        $values = $advert->value->map(function ($value) {
            return [
                'attribute' => $value->attribute->name ?? null,
                'value' => $value->value
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
    public function showAdvertsWithCategoryAndLocations(string $urlPath = null): Response
    {
        $data = $this->categoryService->parseCategoryAndLocationFromUrl($urlPath);
        $adverts = $this->advertService->getAdvertsByCategoryAndLocation(
            $data['categories']->last(),
            $data['childCategories'],
            $data['locations']->last()
        );

        return Inertia::render('Advert/Category', [
            ...$data,
            'adverts' => $adverts
        ]);
    }


    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }
}
