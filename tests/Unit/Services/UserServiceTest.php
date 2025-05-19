<?php

namespace Tests\Unit\Services;

use App\Http\Services\UserService;
use App\Models\Role;
use App\Models\User;
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
        $this->userService = new UserService;
    }

    public function test_create_user_from_admin(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $user = $this->userService->createUserFromAdmin($userData);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_update_user(): void
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

    public function test_delete_user(): void
    {
        $user = User::factory()->create();

        $this->userService->deleteUser($user);

        $this->assertSoftDeleted($user);
    }

    public function test_restore_user(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $this->userService->restoreUser($user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_create_user_with_roles(): void
    {
        $role = Role::factory()->create();

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ];

        $user = $this->userService->createUserFromAdmin($userData);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Перевіряємо, чи ролі були прив'язані
        $this->assertTrue($user->roles->contains($role));
    }
}
