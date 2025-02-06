<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('create-user', function (User $user) {
            return $user->hasPermission('user', 'create');
        });

        Gate::define('edit-user', function (User $user) {
            return $user->hasPermission('user', 'edit');
        });
        Gate::define('delete-user', function (User $user) {
            return $user->hasPermission('user', 'delete');
        });
    }
}
