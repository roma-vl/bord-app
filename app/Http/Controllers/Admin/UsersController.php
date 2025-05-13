<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Repositories\UserRepository;
use App\Http\Services\SearchSortService;
use App\Http\Services\UserService;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserService    $userService,
        private readonly UserRepository $userRepository,
        private readonly SearchSortService $searchSortService
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

    public function create(): JsonResponse
    {
        if (Gate::denies('user.create')) {
            abort(403);
        }

        return response()->json([
            'roles' => Role::all(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        if (Gate::denies('user.create')) {
            abort(403);
        }
        $user = $this->userService->createUser($request->validated());

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return back()->with('success', 'User created successfully!');
    }


    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        if (Gate::denies('user.edit')) {
            abort(403);
        }

        return response()->json([
            'user' => new UserResource($user),
            'roles' => Role::all(),
            'userRoles' => $user->roles->pluck('id')
        ]);
    }


    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('user.edit')) {
            abort(403);
        }

        $this->userService->updateUser($user, $request->validated());

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }


    public function destroy(User $user): RedirectResponse
    {
        if (Gate::denies('user.delete')) {
            abort(403);
        }

        $this->userService->deleteUser($user);
        return redirect()->route('admin.users.index')
            ->with('info', 'Вжух і щось сталося... 🤡 ');
    }

    public function restore(int $id): RedirectResponse
    {
        if (Gate::denies('user.delete')) {
            abort(403);
        }

        $this->userService->restoreUser($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'Вжух і користувач відновлений! 🤡');
    }

    public function search(Request $request): Response
    {
        $perPage = $this->getSessionValue($request, 'per_page', self::PER_PAGE);
        $sortBy = $this->getSessionValue($request, 'sort_by', self::SORT_BY_DEFAULT);
        $sortOrder = $this->getSessionValue($request, 'sort_order', self::SORT_ORDER_DEFAULT);

        $search = $request->input('search');
        $usersQuery = $this->userRepository->getUsersQuery();
        $this->searchSortService->applySearch($usersQuery, $search);
        $usersQuery = $usersQuery->paginate($perPage);


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
