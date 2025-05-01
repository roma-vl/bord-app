<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Advert;
use App\Models\User;

class FavoriteService
{
    public function add(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->addToFavorites($advert->id);
    }
    public function remove(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->removeFromFavorites($advert->id);
    }

    private function getUser(int $userId)
    {
        return User::findOrFail($userId);
    }

    private function getAdvert(int $advertId)
    {
        return Advert::findOrFail($advertId);
    }

}
