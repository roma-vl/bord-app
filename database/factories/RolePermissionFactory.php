<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolePermissionFactory extends Factory
{
    protected $model = RolePermission::class;

    public function definition()
    {
        return [
            'role_id' => Role::factory(),
            'object' => $this->faker->randomElement(['user', 'post', 'category']),
            'operation' => $this->faker->randomElement(['create', 'edit', 'delete', 'view']),
        ];
    }
}
