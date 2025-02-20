<?php

namespace App\Http\Controllers;

use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\LocatedRegion;
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

        $regions = LocatedRegion::all();
        $news = Advert::where('status', 'active')->orderByDesc('id')->limit(4)->get();
        $vip = Advert::where('status', 'active')->where('premium', 1)->orderByDesc('id')->limit(4)->get();


        return Inertia::render('Index', compact('categories', 'vip', 'news', 'regions'));
    }

    public function region(Category $category, LocatedRegion $locatedRegion)
    {
        $regions = LocatedRegion::where('parent_id', $locatedRegion ? $locatedRegion->id : null)
            ->orderBy('name')->get();
        return response()->json([
            'regions' => $regions
        ]);
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

    public function phone(Advert $advert): string
    {
        return $advert->user->phone;
    }
}
