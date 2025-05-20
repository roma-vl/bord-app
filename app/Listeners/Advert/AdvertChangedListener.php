<?php

namespace App\Listeners\Advert;

use App\Events\Advert\AdvertChanged;
use App\Jobs\Advert\ReindexAdvert;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvertChangedListener implements ShouldQueue
{
    public function handle(AdvertChanged $event): void
    {
        ReindexAdvert::dispatch($event->advert);
    }
}
