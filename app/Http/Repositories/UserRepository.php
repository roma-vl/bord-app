<?php

namespace App\Http\Repositories;


use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getPaginatedUsers(int $perPage, string $sortBy, string $sortOrder): LengthAwarePaginator
    {
        return User::with(['roles']) // Додаємо зв'язок ролей
        ->withTrashed()
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);
    }


    public function findUser(int $id): ?User
    {
        return User::withTrashed()->find($id);
    }
}
