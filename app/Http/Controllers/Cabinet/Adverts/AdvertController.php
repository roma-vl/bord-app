<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Services\Adverts\AdvertService;
use App\Http\Services\CategoryService;
use App\Http\Services\LocationService;
use App\Models\Adverts\Category;
use DomainException;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;


class AdvertController extends Controller
{
    const int COUNTRY_ID = 1;
    protected LocationService $locationService;
    private CategoryService $categoryService;
    private AdvertService $advertService;

    public function __construct(LocationService $locationService,
                                CategoryService $categoryService,
                                AdvertService $advertService)
    {
        $this->locationService = $locationService;
        $this->categoryService = $categoryService;
        $this->advertService = $advertService;
    }
    public function index(): Response    {
        return Inertia::render('Account/Advert/Index');
    }

    public function create(): Response
    {
        $categories =  $this->categoryService->getCategories();
        $regions = $this->locationService->getRegions(self::COUNTRY_ID);
        return Inertia::render('Account/Advert/Create', [
            'categories' => $categories,
            'regions' => $regions,
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse|JsonResponse
    {
        $ff  = $request->all();
        try {
            $advert = $this->advertService->create(
                Auth::id(),
                $request
            );
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
       return redirect()->route('account.adverts.index')->with('success', 'Оголошення створено!');
    }

    public function getAreas(int $regionId): JsonResponse
    {
        return response()->json($this->locationService->getAreas($regionId));
    }

    public function getVillages(int $areaId): JsonResponse
    {
        return response()->json($this->locationService->getVillages($areaId));
    }
    public function getAttributes(int $categoryId): JsonResponse
    {
        $category = Category::findOrFail($categoryId);
        $parentAttributes = $category->getParentAttributes()->toArray();
        $attributes = $category->attributes()->orderBy('sort')->get()->toArray();
        return response()->json(array_merge($parentAttributes, $attributes));
    }

}
