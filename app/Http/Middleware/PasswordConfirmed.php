<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordConfirmed
{
    public function handle(Request $request, Closure $next)
    {
        // Якщо користувач не залогінений — пропускаємо далі
        if (! Auth::check()) {
            return $next($request);
        }

        // Не перенаправляти на самому confirm-password
        if ($request->route()->named('password.confirm')) {
            return $next($request);
        }

        // Отримуємо час останнього підтвердження пароля
        $confirmedAt = $request->session()->get('auth.password_confirmed_at', 0);

        // Якщо підтвердження застаріло (20 сек), перенаправляємо
        if ($confirmedAt < time() - 3600) {
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
}
