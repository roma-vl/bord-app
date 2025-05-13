<?php
namespace App\Http\Repositories;

use App\Filters\UserFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    public function getUsersQuery(): Builder
    {
        return User::query();
    }

    public function getFilteredPaginatedUsers(array $params): LengthAwarePaginator
    {
        $filter = new UserFilter($params);

        return User::query()
            ->with('roles')
            ->withTrashed()
            ->filter($filter)
            ->orderBy($params['sort_by'], $params['sort_order'])
            ->paginate($params['per_page']);
    }
}
