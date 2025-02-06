<?php

namespace Tests\Unit\Services;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Http\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function testCreateUser(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $user = $this->userService->createUser($userData);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        $this->assertNotNull($user->email_verified_at);
    }

    public function testUpdateUser(): void
    {
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'newpassword',
            'locale' => 'en',
            'active' => true,
        ];

        $updatedUser = $this->userService->updateUser($user, $updatedData);

        $this->assertEquals('Jane Doe', $updatedUser->name);
        $this->assertEquals('jane@example.com', $updatedUser->email);
        $this->assertTrue(Hash::check('newpassword', $updatedUser->password));
    }

    public function testDeleteUser(): void
    {
        $user = User::factory()->create();

        $this->userService->deleteUser($user);

        $this->assertSoftDeleted($user);
    }

    public function testRestoreUser(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $this->userService->restoreUser($user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function testUserHasCorrectPermissions(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'Admin']);

        $permission1 = RolePermission::factory()->create([
            'role_id' => $role->id,
            'object' => 'user',
            'operation' => 'edit',
        ]);

        $permission2 = RolePermission::factory()->create([
            'role_id' => $role->id,
            'object' => 'user',
            'operation' => 'delete',
        ]);

        $user->roles()->attach($role->id);

        $permissions = (new UserService())->getUserPermissions($user);

        $this->assertContains('user.edit', $permissions);
        $this->assertContains('user.delete', $permissions);
    }



}
