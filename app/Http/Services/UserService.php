<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => bcrypt($data['password']),
            'email_verified_at' => now(),
        ]);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return $user;
    }


    public function updateUser(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = strtolower($data['email']);
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->locale = $data['locale'];
        $user->email_verified_at = $data['active'] ? now() : null;

        $user->save();

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function restoreUser(int $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }

    public function getUserPermissions(User $user): Collection
    {
        return $user->roles()->with('permissions')->get()
            ->flatMap(fn($role) => $role->permissions)
            ->map(fn($permission) => "{$permission->object}.{$permission->operation}")
            ->unique();
    }
}
