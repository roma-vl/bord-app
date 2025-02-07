<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    public function __construct() {
        if (Gate::denies('role')) {
            abort(403);
        }
    }
    public function index(): Response
    {
        $roles = Role::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): JsonResponse
    {
        $permissions = Permission::all();

        return response()->json([
            'permissions' => $permissions,
        ]);
    }

    public function edit(Role $role): JsonResponse
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);

    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'is_enabled' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:role_permissions,id'
        ]);

        $role = Role::create($validated);
        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }


    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'is_enabled' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'is_enabled' => $validated['is_enabled'],
        ]);

        $role->permissions()->sync($validated['permissions']);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }


    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('info', 'Role deleted successfully');
    }


}
