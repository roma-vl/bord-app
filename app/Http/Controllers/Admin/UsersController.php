<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    const int PER_PAGE = 2;
    const int SEARCH_MIN_LENGTH = 1;
    const string SORT_BY_DEFAULT = 'id';
    const string SORT_ORDER_DEFAULT = 'asc';
    protected array $allowedSortFields = ['id', 'name', 'email'];

    public function index(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $usersQuery = User::withTrashed();
        $this->applySorting($usersQuery, $sortBy, $sortOrder);

        $users = UserResource::collection($usersQuery->paginate($perPage));

        return Inertia::render('Admin/Users/Index', compact('users', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Вжух і нічого не сталося... 🤡 ');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('info', 'Вжух і щось сталося... 🤡 ');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Користувача не знайдено.');
        }

        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'Вжух і користувач відновлений! 🤡');
    }


    public function search(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $search = $request->input('search');
        $query = User::withTrashed();
        $this->applySearch($query, $search);
        $this->applySorting($query, $sortBy, $sortOrder);

        $users = UserResource::collection($query->paginate($perPage));

        return Inertia::render('Admin/Users/Index', compact('users', 'sortBy', 'sortOrder'));
    }

    private function getSessionValue(Request $request, string $key, $default)
    {
        $value = $request->query($key, session($key, $default));
        session([$key => $value]);
        return $value;
    }

    private function applySearch($query, ?string $search)
    {
        if ($search && strlen($search) >= self::SEARCH_MIN_LENGTH) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
    }

    private function applySorting($query, string $sortBy, string $sortOrder)
    {
        if (in_array($sortBy, $this->allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }
    }
}
