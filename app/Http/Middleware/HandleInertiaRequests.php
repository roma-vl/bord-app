<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $dd = $this->getUserPermissions($request->user());
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'permissions' => $this->getUserPermissions($request->user()),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'info' => fn() => $request->session()->get('info'),
            ],
        ];
    }

    private function getUserPermissions(?User $user): array
    {
        return $user ? $user->getPermissions() : [];
    }

}
