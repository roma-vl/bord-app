<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\RejectRequest;
use App\Http\Services\Adverts\AdvertService;
use App\Models\Adverts\Advert;
use Inertia\Inertia;

class AdvertsController extends Controller
{
    public function __construct(private readonly AdvertService $advertService) {}

    public function moderation()
    {
        $adverts_moderation = Advert::where('status', Advert::STATUS_MODERATION)->orderByDesc('created_at')->paginate(self::PER_PAGE);

        return Inertia::render('Admin/Advert/Actions/Moderation', [
            'adverts_moderation' => $adverts_moderation,
        ]);
    }

    public function active(Advert $advert)
    {
        $this->advertService->moderate($advert);

        return back()->with('success', 'Advert is publish successfully!');
    }

    public function reject(Advert $advert, RejectRequest $request)
    {
        $this->advertService->reject($advert, $request);

        return back()->with('success', 'Advert is Rejected successfully!');
    }
}
