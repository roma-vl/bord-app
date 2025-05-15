<?php

namespace App\Http\Controllers\Api\Cabinet;

use App\Http\Resources\Api\Advert\AdvertDetailResource;
use App\Http\Services\Adverts\FavoriteService;
use App\Models\Adverts\Advert;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class FavoriteController
{
    public function __construct(private readonly FavoriteService $service){}

    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);

        return AdvertDetailResource::collection($adverts);
    }

    public function remove(Advert $advert): RedirectResponse
    {
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.favorites.index');
    }
}
