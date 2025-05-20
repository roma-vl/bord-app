<?php

namespace App\Events\Advert;

use App\Models\Adverts\Advert;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdvertChanged
{
    use Dispatchable, SerializesModels;

    public Advert $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }
}
