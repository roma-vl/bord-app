<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Http\Services\Adverts\FavoriteService;
use App\Models\Adverts\Advert;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class FavoriteController
{
    public function __construct(
        private readonly FavoriteService  $favoriteService
    ){}

    public function add(Advert $advert)
    {
        $this->favoriteService->add(Auth::id(), $advert->id);
        return response()->json([], Response::HTTP_CREATED);
    }

    public function remove(Advert $advert)
    {
        $this->favoriteService->remove(Auth::id(), $advert->id);
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
