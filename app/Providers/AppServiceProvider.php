<?php

namespace App\Providers;

use App\Models\Adverts\Advert;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerSearchClient();
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

    private function registerSearchClient(): void
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    private function bootSearchable(): void
    {
        Advert::bootSearchable();
    }
}
