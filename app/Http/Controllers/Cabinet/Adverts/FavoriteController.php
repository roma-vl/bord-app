<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Services\Adverts\FavoriteService;
use App\Models\Adverts\Advert;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteController
{
    private FavoriteService $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {

        $this->favoriteService = $favoriteService;
    }
    public function index(): Response    {
        $favoriteAdverts = Advert::favoriteByUser(Auth::user())->orderByDesc('id')->paginate(10);
        return Inertia::render('Account/Favorites/Index', [
            'favoriteAdverts' => $favoriteAdverts
        ]);
    }
    public function add(Advert $advert)
    {
        try {
            $this->favoriteService->add(auth()->id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert)->with('success', 'Оголошення додано до обраних');
    }
    public function remove(Advert $advert)
    {
        try {
            $this->favoriteService->remove(auth()->id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->back();
    }
}
