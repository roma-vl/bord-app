<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Middleware;

class ShareLocale extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $this->getLocale();
        App::setLocale($locale);

        Inertia::share([
            'locale' => $locale,
        ]);

        return $next($request);
    }

    public function getLocale(): string
    {
        return Auth::user()?->locale ?? Session::get('locale') ?? App::getLocale();
    }
}
