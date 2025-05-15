<?php

namespace App\Http\Services;

use App\Http\Requests\ProfileUpdateRequest;

class ProfileService
{
    public function updateUser(ProfileUpdateRequest $request): void
    {
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $oldPhone = $request->user()->phone;

        $user = \Auth::user();
        $user->update($request->all());

        if ($oldPhone !== $user->phone) {
            $user->unverifyPhone();
        }
    }
}
