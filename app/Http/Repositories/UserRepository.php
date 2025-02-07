<?php

namespace App\Http\Repositories;


use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getPaginatedUsers(int $perPage, string $sortBy, string $sortOrder): LengthAwarePaginator
    {
        return User::with(['roles'])
        ->withTrashed()
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    public function getUsersQuery(): Builder
    {
        return User::query();
    }
}
