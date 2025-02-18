<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Category;
use App\Models\LocatedRegion;

class CreateController extends Controller
{
    public function __construct()
    {

    }

    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function region(Category $category, LocatedRegion $locatedRegion)
    {
        $regions = LocatedRegion::where('parent_id', $locatedRegion ? $locatedRegion->id : null)
            ->orderBy('name')->get();
        return response()->json([
            'regions' => $regions
        ]);
    }

    public function advert(Category $category, LocatedRegion $locatedRegion)
    {
        return response()->json([
            'category' => $category,
            'region' => $locatedRegion
        ]);
    }

}
