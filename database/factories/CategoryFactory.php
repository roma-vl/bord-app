<?php

namespace Database\Factories;

use App\Models\Adverts\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $name = $this->faker->word;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => null, // або додати логіку для вкладеності
        ];
    }
}
