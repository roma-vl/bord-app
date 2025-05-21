<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleService
{
    public array $locales = ['en', 'uk'];

    public function changeLocale(string $locale)
    {
        if (! in_array($locale, $this->locales)) {
            abort(400);
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        session()->flash('success', trans('auth.language'));
    }
}
