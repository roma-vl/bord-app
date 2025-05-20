<?php

namespace App\Providers;

use App\Models\Adverts\Advert;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (auth()->user()) {
            $permissions = Permission::pluck('key')->toArray();

            foreach ($permissions as $permission) {
                Gate::define($permission, function (User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }

        Gate::define('horizon', function (User $user) {
            return $user->isAdmin() || $user->isModerator();
        });

        Gate::define('manage-own-advert', function (User $user, Advert $advert) {
            return $advert->user_id === $user->id;
        });
    }
}
