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
            }])->orderBy('name')->get();

        $news = Advert::where('status', 'active')->orderByDesc('id')->limit(4)->get();
        $vip = Advert::where('status', 'active')->where('premium', 1)->orderByDesc('id')->limit(4)->get();

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
        return response()->json(['regions' => []]); // Мінімум 2 символи
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
    public function showCategory($urlPath = null)
    {
        $cacheKey = 'showCategory_' . md5($urlPath);

        $data = Cache::tags([Location::class, Category::class])->rememberForever($cacheKey, function () use ($urlPath) {
            $slugs = $urlPath ? array_reverse(explode('/', $urlPath)) : [];

            $locations = [];
            $categories = [];

            foreach ($slugs as $slug) {
                if ($location = Location::where('slug', $slug)->first()) {
                    $locations[] = $location;
                } else {
                    $categories[] = $slug;
                }
            }

            $currentLocation = $locations ? $locations[0] : null;
            $parentLocations = $currentLocation ? $currentLocation->ancestors()->orderBy('name')->get(['id', 'name', 'slug']) : collect();
            $selectedLocation = $parentLocations->count() >= 2 ? $parentLocations->get(1) : null;

            $filteredLocations = $selectedLocation
                ? collect([$selectedLocation, $currentLocation])->filter()
                : collect([$selectedLocation])->filter();

            $currentCategory = $categories ? $categories[0] : null;
            if ($currentCategory) {
                $currentCategory = Category::where('slug', $categories[0])->first();
            }

            $parentCategory = $currentCategory
                ? $currentCategory->ancestors()->orderBy('name')->get(['id', 'name', 'slug'])
                : collect();
            $childCategory = $currentCategory
                ? $currentCategory->descendants()->orderBy('name')->get(['id', 'name', 'slug'])
                : collect();
            $filteredCategory = $currentCategory ? $parentCategory->push($currentCategory) : collect();

            return [
                'locations' => $filteredLocations,
                'categories' => $filteredCategory,
                'childCategories' => $childCategory,
            ];
        });

//        Cache::tags(['locations'])->flush();
        return Inertia::render('Advert/Category', $data);
    }

    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }
}
