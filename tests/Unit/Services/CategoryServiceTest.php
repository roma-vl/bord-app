<?php

namespace Tests\Unit\Services;

use App\Http\Services\CategoryService;
use App\Models\Adverts\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = new CategoryService;
    }

    public function test_it_can_get_categories()
    {

        $this->categoryService->createCategory([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'parent_id' => null,
        ]);

        $this->categoryService->createCategory([
            'name' => 'Test Category 2',
            'slug' => 'test-category-2',
            'parent_id' => null,
        ]);

        $categories = $this->categoryService->getCategories();

        $this->assertCount(2, $categories);
    }

    public function test_it_can_create_category()
    {
        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'parent_id' => null,
        ];

        $category = $this->categoryService->createCategory($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertDatabaseHas('advert_categories', ['name' => 'Test Category']);
    }

    public function test_it_generates_slug_if_not_provided()
    {
        $data = ['name' => 'New Category'];

        $category = $this->categoryService->createCategory($data);

        $this->assertNotEmpty($category->slug);
        $this->assertEquals('new-category', $category->slug);
    }

    public function test_it_can_update_category()
    {
        $category = $this->categoryService->createCategory([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'parent_id' => null,
        ]);

        $updatedData = ['name' => 'Updated Name'];

        $this->categoryService->updateCategory($category, $updatedData);

        $this->assertEquals('Updated Name', $category->fresh()->name);
    }

    public function test_it_deletes_category()
    {
        $category = $this->categoryService->createCategory([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'parent_id' => null,
        ]);

        $this->categoryService->deleteCategory($category);

        $this->assertDatabaseMissing('advert_categories', ['id' => $category->id]);
    }

    public function test_it_can_move_category_up()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('up')->once();

        $this->categoryService->moveUp($category);
    }

    public function test_it_can_move_category_down()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('down')->once();

        $this->categoryService->moveDown($category);
    }

    public function test_it_can_move_category_to_top()
    {
        $category = Mockery::mock(Category::class);
        $first = Mockery::mock(Category::class);
        $category->shouldReceive('siblings->defaultOrder->first')->andReturn($first);
        $category->shouldReceive('insertBeforeNode')->with($first)->once();

        $this->categoryService->moveToTop($category);
    }

    public function test_it_can_move_category_to_bottom()
    {
        $category = Mockery::mock(Category::class);
        $last = Mockery::mock(Category::class);
        $category->shouldReceive('siblings->defaultOrder->first')->andReturn($last);
        $category->shouldReceive('insertAfterNode')->with($last)->once();

        $this->categoryService->moveToBottom($category);
    }
}
