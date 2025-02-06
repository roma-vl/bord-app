<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $editorRole = Role::where('name', 'Editor')->first();
        $userRole = Role::where('name', 'User')->first();

        RolePermission::factory()->create(['role_id' => $adminRole->id, 'object' => 'user', 'operation' => 'create']);
        RolePermission::factory()->create(['role_id' => $adminRole->id, 'object' => 'user', 'operation' => 'delete']);
        RolePermission::factory()->create(['role_id' => $editorRole->id, 'object' => 'user', 'operation' => 'edit']);
        RolePermission::factory()->create(['role_id' => $userRole->id, 'object' => 'user', 'operation' => 'view']);
    }
}
