<?php

namespace App\Providers;

use App\Http\Services\Banner\CostCalculatorService;
use App\Models\Adverts\Advert;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerCostCalculator();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $locale = Auth::user()?->locale ?? Session::get('locale') ?? config('app.locale');
        App::setLocale($locale);
        Vite::prefetch(concurrency: 3);
        $this->bootSearchable();
    }

    private function registerCostCalculator(): void
    {
        $this->app->singleton(CostCalculatorService::class, function (Application $app) {
            $config = $app->make('config')->get('banner');
            return new CostCalculatorService($config['price']);
        });
    }

    private function bootSearchable(): void
    {
        Advert::bootSearchable();
    }
}
