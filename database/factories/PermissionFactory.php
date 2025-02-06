<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'key' => $this->faker->unique()->randomElement([
                'user.create', 'user.edit', 'user.delete', 'user.view',
                'post.create', 'post.edit', 'post.delete', 'post.view',
                'category.create', 'category.edit', 'category.delete', 'category.view',
            ]),
            'description' => $this->faker->sentence(),
        ];
    }
}
