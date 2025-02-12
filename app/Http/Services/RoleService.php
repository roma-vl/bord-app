<?php

namespace App\Http\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;

class RoleService
{
    public function getAllRoles(): Collection
    {
        return Role::orderBy('id', 'desc')->get();
    }

    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    public function getRoleWithPermissions(Role $role): array
    {
        return [
            'role' => $role,
            'permissions' => $this->getAllPermissions(),
            'rolePermissions' => $role->permissions->pluck('id')->toArray(),
        ];
    }

    public function createRole(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'is_enabled' => $data['is_enabled'] ?? false,
        ]);

        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return $role;
    }

    public function updateRole(Role $role, array $data): void
    {
        $role->update([
            'name' => $data['name'],
            'is_enabled' => $data['is_enabled'] ?? false,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);
    }

    public function deleteRole(Role $role): void
    {
        $role->delete();
    }
}
