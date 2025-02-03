<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Repositories\UserRepository;
use App\Http\Services\SearchSortService;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    const int PER_PAGE = 2;
    const string SORT_BY_DEFAULT = 'id';
    const string SORT_ORDER_DEFAULT = 'asc';

    public function __construct(
        private UserService $userService,
        private UserRepository $userRepository,
        private SearchSortService $searchSortService
    ) {}

    public function index(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $usersQuery = $this->userRepository->getPaginatedUsers($perPage, $sortBy, $sortOrder);
        $users = UserResource::collection($usersQuery);

        return Inertia::render('Admin/Users/Index', compact('users', 'sortBy', 'sortOrder'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $this->userService->createUser($validated);

        return back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6',
            'active' => 'required|boolean',
            'locale' => 'required|in:' . implode(',', User::LOCALES),
        ]);

        $this->userService->updateUser($user, $validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteUser($user);
        return redirect()->route('admin.users.index')
            ->with('info', 'Вжух і щось сталося... 🤡 ');
    }

    public function restore(int $id): RedirectResponse
    {
        $user = $this->userService->restoreUser($id);
        return redirect()->route('admin.users.index')
            ->with('success', 'Вжух і користувач відновлений! 🤡');
    }

    public function search(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $search = $request->input('search');
        $usersQuery = $this->userRepository->getPaginatedUsers($perPage, $sortBy, $sortOrder);
        $this->searchSortService->applySearch($usersQuery, $search);

        $users = UserResource::collection($usersQuery);

        return Inertia::render('Admin/Users/Index', compact('users', 'sortBy', 'sortOrder'));
    }

    private function getSessionValue(Request $request, string $key, $default)
    {
        $value = $request->query($key, session($key, $default));
        session([$key => $value]);
        return $value;
    }
}
