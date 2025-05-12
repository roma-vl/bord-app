<?php
namespace App\Http\Controllers;
use App\Http\Services\Banner\BannerService;
use App\Models\Banners\Banner;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;

class BannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request)
    {
        $format = $request['format'];
        $category = $request['category'];
        $region = $request['region'];

        if (!$banner = $this->service->getRandomForView($category, $region, $format)) {
            return '';
        }

        $banner->width= $banner->getWidth();
        $banner->height= $banner->getHeight();

        return Inertia::render('Banner/Get', [
            'banner' => $banner,
        ]);
    }

    public function click(Banner $banner): Application|Redirector|RedirectResponse
    {
        $this->service->click($banner);
        return redirect($banner->url);
    }
}
