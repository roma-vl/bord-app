<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::where('name', 'Admin')->first();
        $editor = Role::where('name', 'Editor')->first();
        $user = Role::where('name', 'User')->first();

        $permissions = Permission::all()->pluck('id', 'key');

        $adminPermissions = [
            'user.create', 'user.edit', 'user.delete', 'user.view',
            'post.create', 'post.edit', 'post.delete', 'post.view',
            'category.create', 'category.edit', 'category.delete', 'category.view',
        ];

        $editorPermissions = [
            'post.create', 'post.edit', 'post.delete', 'post.view',
            'category.create', 'category.edit', 'category.view',
        ];

        $userPermissions = ['post.view', 'category.view'];

        $admin->permissions()->sync($permissions->only($adminPermissions)->values()->all());
        $editor->permissions()->sync($permissions->only($editorPermissions)->values()->all());
        $user->permissions()->sync($permissions->only($userPermissions)->values()->all());
    }
}
