<?php

namespace Tests\Unit\Services;

use App\Http\Services\SearchSortService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSortServiceTest extends TestCase
{
    use RefreshDatabase;

    private SearchSortService $searchSortService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchSortService = new SearchSortService();
    }

    public function testApplySearchUser(): void
    {
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
        User::factory()->create(['name' => 'Alice Johnson', 'email' => 'alice@example.com']);

        $query = User::query();

        // Застосовуємо пошук
        $this->searchSortService->applySearch($query, 'Jane');

        $this->assertEquals(1, $query->count());
        $this->assertEquals('Jane Smith', $query->first()->name);
    }

    public function testApplySearchEmail(): void
    {
        User::factory()->create(['name' => 'John Doe1', 'email' => 'john2@example.com']);
        User::factory()->create(['name' => 'Jane Smith1', 'email' => 'jane2@example.com']);
        User::factory()->create(['name' => 'Alice Johnson1', 'email' => 'alice2@example.com']);

        $query = User::query();

        $this->searchSortService->applySearch($query, 'john2@example.com');

        $this->assertEquals(1, $query->count());
        $this->assertEquals('john2@example.com', $query->first()->email);
    }
}
