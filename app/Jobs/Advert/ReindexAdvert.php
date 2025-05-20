<?php

namespace App\Jobs\Advert;

use App\Models\Adverts\Advert;
use App\Services\Search\AdvertIndexer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReindexAdvert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Advert $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    public function handle(AdvertIndexer $indexer): void
    {
        $indexer->index($this->advert);
    }
}
