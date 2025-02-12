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
        $this->roleService = new RoleService();
    }

    public function testGetAllRoles(): void
    {
        Role::factory(3)->create();

        $roles = $this->roleService->getAllRoles();

        $this->assertCount(3, $roles);
    }

    public function testGetAllPermissions(): void
    {
        Permission::factory(5)->create();

        $permissions = $this->roleService->getAllPermissions();

        $this->assertCount(5, $permissions);
    }

    public function testCreateRole(): void
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

    public function testUpdateRole(): void
    {
        $role = Role::factory()->create(['name' => 'User']);

        $data = [
            'name' => 'Moderator',
            'is_enabled' => false,
        ];

        $this->roleService->updateRole($role, $data);
        $role->refresh();

        $this->assertEquals('Moderator', $role->name);
        $this->assertFalse((boolean)$role->is_enabled);
    }

    public function testDeleteRole(): void
    {
        $role = Role::factory()->create();

        $this->roleService->deleteRole($role);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function testRoleRequestValidation(): void
    {
        $data = [
            'name' => '',
            'is_enabled' => 'invalid',
            'permissions' => [999],
        ];

        $request = new RoleRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('is_enabled', $validator->errors()->toArray());
        $this->assertArrayHasKey('permissions.0', $validator->errors()->toArray());
    }
}
