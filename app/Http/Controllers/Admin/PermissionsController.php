<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Permission;

class PermissionsController extends Controller
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        if (Gate::denies('permission')) {
            abort(403);
        }
        $this->permissionService = $permissionService;
//        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index(): Response
    {
        $permissions = $this->permissionService->getAll();

        return Inertia::render('Admin/Permissions/Index', compact('permissions'));
    }

    public function create(): JsonResponse
    {
        return response()->json([]);
    }

    public function edit(Permission $permission): JsonResponse
    {
        return response()->json(['permission' => $permission]);
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        $this->permissionService->create($request->validated());

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->permissionService->update($permission, $request->validated());

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->permissionService->delete($permission);

        return redirect()->route('admin.permissions.index')->with('info', 'Permission deleted successfully');
    }
}

