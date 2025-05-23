<?php

namespace App\Listeners\Advert;

use App\Events\Advert\ModerationPassed;
use App\Notifications\Advert\ModerationPassedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModerationPassedListener implements ShouldQueue
{
    public function handle(ModerationPassed $event): void
    {
        $advert = $event->advert;
        $advert->user->notify(new ModerationPassedNotification($advert));
    }
}
