<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\RejectRequest;
use App\Http\Requests\Adverts\AdvertFilterRequest;
use App\Http\Resources\Admin\Advert\AdvertResource;
use App\Http\Services\Adverts\AdvertService;
use App\Models\Adverts\Advert;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdvertsController extends Controller
{
    public function __construct(private readonly AdvertService $advertService) {}

    public function index(ADvertFilterRequest $request): Response
    {
        $validated = $request->validatedWithDefaults();
        $adverts = AdvertResource::collection($this->advertService->getFilteredPaginatedAdverts($validated));

        return Inertia::render('Admin/Advert/Index', [
            'adverts' => $adverts,
        ]);

    }

    public function search(ADvertFilterRequest $request): Response
    {
        $validated = $request->validatedWithDefaults();
        $adverts = AdvertResource::collection($this->advertService->getFilteredPaginatedAdverts($validated));

        return Inertia::render('Admin/Advert/Index', [
            'adverts' => $adverts,
        ]);

    }

    public function moderation(): Response
    {
        $adverts_moderation = Advert::where('status', Advert::STATUS_MODERATION)
            ->orderByDesc('created_at')
            ->paginate(self::PER_PAGE);

        return Inertia::render('Admin/Advert/Actions/Moderation', [
            'adverts_moderation' => $adverts_moderation,
        ]);
    }

    public function active(Advert $advert): RedirectResponse
    {
        $this->advertService->moderate($advert);

        return back()->with('success', __('adverts.advert_is_publish'));
    }

    public function reject(Advert $advert, RejectRequest $request): RedirectResponse
    {
        $this->advertService->reject($advert, $request);

        return back()->with('success', __('adverts.advert_is_rejected'));
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        return back()->with('success', __('adverts.advert_is_deleted'));
    }

    public function restore(Advert $advert): RedirectResponse
    {
        return back()->with('success', __('adverts.advert_is_restored'));
    }

    public function orders(): Response
    {
        return Inertia::render('Admin/Advert/Index', [
            'orders' => $this->advertOrderService->getAdvertOrders(),
        ]);
    }
}
