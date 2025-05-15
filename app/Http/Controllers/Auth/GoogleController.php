<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ){}
    public function redirectToGoogle(): SymfonyRedirectResponse|RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): Application|Redirector|RedirectResponse
    {
        try {
            $user = $this->userService->createUserFromGoogle();
            Auth::login($user, true);
            return redirect()->intended('/');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Помилка при авторизації через Google.');
        }
    }

}
