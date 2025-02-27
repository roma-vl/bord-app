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
use Illuminate\Support\Facades\Gate;
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

        $advert->load(['category.ancestors', 'value.attribute', 'photo', 'user', 'region', 'area', 'village']);

        $categoryAttributes = $advert->category->allArrayAttributes();

        $values = $advert->value->map(function ($value) {
            return [
                'attribute' => $value->attribute->name ?? null,
                'value' => $value->value
            ];
        });

        return Inertia::render('Advert/Show', [
            'advert' => $advert,
            'category' => $advert->category,
            'categoryAttributes' => $categoryAttributes,
            'values' => $values,
            'user' => $advert->values,
            'region' => $advert->region . $advert->area . $advert->village,
            'photos' => $advert->photo,
        ]);
    }

    public function showCategory($categorySlug, $subCategorySlug = null, $citySlug = null)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $subCategory = $subCategorySlug
            ? Category::where('slug', $subCategorySlug)->where('parent_id', $category->id)->firstOrFail()
            : null;

        $subCategories = $subCategory ?
            $subCategory->descendants()->orderBy('name')->get(['id', 'name', 'slug']) :
            $category->descendants()->orderBy('name')->get(['id', 'name', 'slug']);

        $city = $citySlug ? Location::where('slug', $citySlug)->firstOrFail() : null;
        return Inertia::render('Advert/Category',
            compact('category', 'subCategory','subCategories', 'city'));
    }

    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }
}
