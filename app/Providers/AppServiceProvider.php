<?php

namespace App\Providers;

use App\Http\Services\Banner\CostCalculatorService;
use App\Models\Adverts\Advert;
use DateInterval;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerCostCalculator();

        Passport::enablePasswordGrant();
        Passport::hashClientSecrets();

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

        Passport::ignoreRoutes();
        Passport::loadKeysFrom(storage_path());

        $this->app->bind(AuthorizationServer::class, function ($app) {
            $server = new AuthorizationServer(
                $app->make(ClientRepository::class),
                $app->make(AccessTokenRepository::class),
                $app->make(ScopeRepository::class),
                'file://'.storage_path('oauth-private.key'),
                'file://'.storage_path('oauth-public.key')
            );

            // додаємо PasswordGrant
            $passwordGrant = new PasswordGrant(
                $app->make(UserRepository::class),
                $app->make(RefreshTokenRepository::class)
            );
            $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M'));

            $server->enableGrantType(
                $passwordGrant,
                new DateInterval('PT1H') // токен на 1 год
            );

            // додаємо RefreshTokenGrant
            $refreshTokenGrant = new RefreshTokenGrant(
                $app->make(RefreshTokenRepository::class)
            );
            $refreshTokenGrant->setRefreshTokenTTL(new DateInterval('P1M'));

            $server->enableGrantType(
                $refreshTokenGrant,
                new DateInterval('PT1H')
            );

            return $server;
        });

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
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
