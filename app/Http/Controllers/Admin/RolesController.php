<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    public function index(): Response
    {
        $roles = Role::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'is_enabled' => 'boolean',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role): Response
    {
        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'is_enabled' => 'boolean',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('info', 'Role deleted successfully');
    }
}
