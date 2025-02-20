<?php


namespace App\Console\Commands\Advert;

use App\Http\Services\Adverts\AdvertService;
use App\Models\Adverts\Advert;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AdvertExpire extends Command
{
    protected $signature = 'advert:expire';
    protected $description = 'Закриває закінчені оголошення';

    private $service;

    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $success = true;

        foreach (Advert::active()->where('expired_at', '<', Carbon::now())->cursor() as $advert) {
            try {
                $this->service->expire($advert);
            } catch (\DomainException $e) {
                $this->error($e->getMessage());
                $success = false;
            }
        }

        return $success;
    }
}
