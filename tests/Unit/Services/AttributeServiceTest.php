<?php

namespace Tests\Unit\Services;

use App\Http\Services\AttributeService;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AttributeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_attribute()
    {
        // Створюємо мок категорії
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('attributes')
            ->once()
            ->andReturnSelf();
        $category->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Test Attribute',
                'type' => 'text',
                'is_required' => true,
                'variants' => ['option1', 'option2'],
                'sort' => 1,
            ])
            ->andReturn(new Attribute([
                'name' => 'Test Attribute',
                'type' => 'text',
                'is_required' => true,
                'variants' => ['option1', 'option2'],
                'sort' => 1,
            ]));

        $data = [
            'name' => 'Test Attribute',
            'type' => 'text',
            'is_required' => true,
            'variants' => "option1\noption2",
            'sort' => 1,
        ];

        $service = new AttributeService;
        $attribute = $service->create($category, $data);

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertEquals('Test Attribute', $attribute->name);
        $this->assertEquals('text', $attribute->type);
        $this->assertTrue($attribute->is_required);
        //        $this->assertCount(2, $attribute->variant);
    }

    public function test_update_attribute()
    {
        // Створюємо мок атрибута
        $attribute = Mockery::mock(Attribute::class);
        $attribute->shouldReceive('update')
            ->once()
            ->with([
                'name' => 'Updated Attribute',
                'type' => 'select',
                'is_required' => false,
                'variants' => ['option3'],
                'sort' => 2,
            ]);

        $data = [
            'name' => 'Updated Attribute',
            'type' => 'select',
            'is_required' => false,
            'variants' => 'option3',
            'sort' => 2,
        ];

        $service = new AttributeService;
        $updatedAttribute = $service->update($attribute, $data);

        $this->assertInstanceOf(Attribute::class, $updatedAttribute);
    }

    public function test_delete_attribute()
    {
        // Створюємо мок атрибута
        $attribute = Mockery::mock(Attribute::class);
        $attribute->shouldReceive('delete')
            ->once();

        $service = new AttributeService;
        $service->delete($attribute);

        // Перевіряємо, що delete був викликаний
        $this->assertTrue(true);
    }
}
