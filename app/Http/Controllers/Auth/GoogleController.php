<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Шукаємо юзера з цим email
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                if ($existingUser->google_id !== $googleUser->getId()) {
                    return redirect('/')
                        ->with('error', 'Цей email вже використовується. Спробуйте увійти звичайним способом.');
                }

                $user = $existingUser;
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);

                $user->roles()->sync([3]);
            }

            Auth::login($user, true);
            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Помилка при авторизації через Google.');
        }
    }

}
