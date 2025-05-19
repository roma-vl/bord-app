<?php

namespace Tests\Unit\Services;

use App\Http\Requests\Admin\RoleRequest;
use App\Http\Services\RoleService;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    private RoleService $roleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleService = new RoleService;
    }

    public function test_get_all_roles(): void
    {
        Role::factory(3)->create();

        $roles = $this->roleService->getAllRoles();

        $this->assertCount(3, $roles);
    }

    public function test_get_all_permissions(): void
    {
        Permission::factory(5)->create();

        $permissions = $this->roleService->getAllPermissions();

        $this->assertCount(5, $permissions);
    }

    public function test_create_role(): void
    {
        $data = [
            'name' => 'Admin',
            'is_enabled' => true,
        ];

        $role = $this->roleService->createRole($data);

        $this->assertDatabaseHas('roles', [
            'name' => 'Admin',
            'is_enabled' => true,
        ]);
    }

    public function test_update_role(): void
    {
        $role = Role::factory()->create(['name' => 'User']);

        $data = [
            'name' => 'Moderator',
            'is_enabled' => false,
        ];

        $this->roleService->updateRole($role, $data);
        $role->refresh();

        $this->assertEquals('Moderator', $role->name);
        $this->assertFalse((bool) $role->is_enabled);
    }

    public function test_delete_role(): void
    {
        $role = Role::factory()->create();

        $this->roleService->deleteRole($role);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_role_request_validation(): void
    {
        $data = [
            'name' => '',
            'is_enabled' => 'invalid',
            'permissions' => [999],
        ];

        $request = new RoleRequest;
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('is_enabled', $validator->errors()->toArray());
        $this->assertArrayHasKey('permissions.0', $validator->errors()->toArray());
    }
}
