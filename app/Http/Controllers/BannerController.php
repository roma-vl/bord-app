<?php

namespace App\Http\Controllers;

use App\Http\Services\Banner\BannerService;
use App\Models\Banners\Banner;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class BannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request)
    {
        $format = $request->get('format');
        $category = $request->get('category');
        $region = $request->get('region');

        $banner = $this->service->getRandomForView($category, $region, $format);

        if (! $banner) {
            return response()->json(['banner' => null]);
        }

        $banner->width = $banner->getWidth();
        $banner->height = $banner->getHeight();

        return response()->json(['banner' => $banner]);
    }

    public function click(Banner $banner): Application|Redirector|RedirectResponse
    {
        $this->service->click($banner);

        return redirect($banner->url);
    }
}
