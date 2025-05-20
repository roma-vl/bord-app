<?php

namespace App\Events\Advert;

use App\Models\Adverts\Advert;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModerationPassed
{
    use Dispatchable, SerializesModels;

    public $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }
}
