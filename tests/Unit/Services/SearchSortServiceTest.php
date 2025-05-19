<?php

namespace Tests\Unit\Services;

use App\Http\Services\SearchSortService;
use App\Http\Services\UserService;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSortServiceTest extends TestCase
{
    use RefreshDatabase;

    private SearchSortService $searchSortService;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchSortService = new SearchSortService;
        $this->userService = new UserService;
    }

    public function test_apply_search_user(): void
    {
        $role = Role::factory()->create();
        $this->userService->createUserFromAdmin([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ]);

        $this->userService->createUserFromAdmin([
            'name' => 'John Doe2',
            'email' => 'john2@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ]);

        $this->userService->createUserFromAdmin([
            'name' => 'John Doe3',
            'email' => 'john3@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ]);

        $query = User::query();

        // Застосовуємо пошук
        $this->searchSortService->applySearch($query, 'Doe2');

        $this->assertEquals(1, $query->count());
        $this->assertEquals('John Doe2', $query->first()->name);
    }

    public function test_apply_search_email(): void
    {
        $role = Role::factory()->create();
        $this->userService->createUserFromAdmin([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ]);

        $this->userService->createUserFromAdmin([
            'name' => 'John Doe2',
            'email' => 'john2@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ]);

        $this->userService->createUserFromAdmin([
            'name' => 'John Doe3',
            'email' => 'john3@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ]);

        $query = User::query();

        $this->searchSortService->applySearch($query, 'john2@example.com');

        $this->assertEquals(1, $query->count());
        $this->assertEquals('john2@example.com', $query->first()->email);
    }
}
