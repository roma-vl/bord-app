<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PermissionsController extends Controller
{
    public function __construct() {
        if (Gate::denies('permission')) {
            abort(403);
        }
    }
    public function index(): Response
    {
        $permissions = Permission::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => $permissions,
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([]);
    }

    public function edit(Permission $permission): JsonResponse
    {
        return response()->json(['permission' => $permission]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:permissions,key',
            'description' => 'nullable|string',
        ]);

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:permissions,key,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('info', 'Permission deleted successfully');
    }
}
