<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\GetRequest;
use App\Http\Services\Banner\BannerService;
use App\Models\Banners\Banner;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class BannerController extends Controller
{
    public function __construct(private readonly BannerService $bannerService) {}

    public function get(GetRequest $request): JsonResponse
    {
        $banner = $this->bannerService->getRandomForView($request);
        $banner->width = $banner->getWidth();
        $banner->height = $banner->getHeight();

        return response()->json([
            'banner' => $banner,
        ]);
    }

    public function click(Banner $banner): Application|Redirector|RedirectResponse
    {
        $this->bannerService->click($banner);

        return redirect($banner->url);
    }
}
