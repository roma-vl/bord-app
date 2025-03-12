<?php

namespace App\Providers;

use App\Models\Adverts\Category;
use App\Models\Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ClearCacheServiceProvider extends ServiceProvider
{
    private $classes = [
        Location::class,
        Category::class,
    ];
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach ($this->classes as $class) {
            $this->registerFlusher($class);
        }
    }

    private function registerFlusher(string $class): void
    {
        $flush = function () use ($class) {
            Cache::tags($class)->flush();
        };

        $class::created($flush);
        $class::saved($flush);
        $class::updated($flush);
        $class::deleted($flush);
    }

}
