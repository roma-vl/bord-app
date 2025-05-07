<?php

namespace App\Http\Controllers;

use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\LocatedRegion;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
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
        $categories = Category::whereNull('parent_id')
            ->with(['rootWithOneChildren' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $news = Advert::where('status', 'active')
            ->with(['firstPhoto', 'favorites'])
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $vip = Advert::where('status', 'active')
            ->where('premium', 1)
            ->with(['firstPhoto', 'favorites'])
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return Inertia::render('Index', compact('categories', 'vip', 'news'));
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
    public function search( $region): JsonResponse
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

    public function show(Advert $advert)
    {
        $advert->load(['category.ancestors', 'value.attribute', 'photo', 'user', 'region','favorites']);
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
    public function showAdvertsWithCategoryAndLocations($urlPath = null): Response
    {
        $cacheKey = 'showCategory_' . md5($urlPath);

        $data = Cache::tags([Location::class, Category::class])->rememberForever($cacheKey, function () use ($urlPath) {
            $slugs = $urlPath ? array_reverse(explode('/', $urlPath)) : [];

            $locations = [];
            $categorySlugs = [];

            foreach ($slugs as $slug) {
                if ($location = Location::where('slug', $slug)->first()) {
                    $locations[] = $location;
                } else {
                    $categorySlugs[] = $slug;
                }
            }

            $currentLocation = $locations ? $locations[0] : null;
            $parentLocations = $currentLocation ? $currentLocation->ancestors()->orderBy('name')->get(['id', 'name', 'slug']) : collect();
            $selectedLocation = $parentLocations->count() >= 2 ? $parentLocations->get(1) : null;

            $filteredLocations = $selectedLocation
                ? collect([$selectedLocation, $currentLocation])->filter()
                : collect([$selectedLocation])->filter();

            $categories = collect();

            foreach (array_reverse($categorySlugs) as $slug) {
                $query = Category::where('slug', $slug);

                $category = $query->first();
                if (!$category) {
                    abort(404);
                }

                $categories->push($category);
            }

            $currentCategory = $categories->last();
            $childCategory = $currentCategory
                ? $currentCategory->descendants()->orderBy('name')->get(['id', 'name', 'slug'])
                : collect();

            return [
                'locations' => $filteredLocations,
                'categories' => $categories,
                'childCategories' => $childCategory,
            ];
        });

        $query = Advert::query()
            ->where('status', 'active')
            ->with(['firstPhoto', 'favorites'])
            ->orderByDesc('id');

        $region = $data['locations']->last();
        $category = $data['categories']->last();

        if ($region) {
            $query->where('region_id', $region->id);
        }

        if ($category) {
            $childIds = $data['childCategories']->pluck('id')->toArray();
            $allCategoryIds = array_merge([$category->id], $childIds);

            $query->whereIn('category_id', $allCategoryIds)
                ->orderByRaw("FIELD(category_id, " . implode(',', $allCategoryIds) . ")");
        }

        $adverts = $query->paginate(3)->withQueryString();

        return Inertia::render('Advert/Category', [
            ...$data,
            'adverts' => $adverts,
        ]);
    }


    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }
}
