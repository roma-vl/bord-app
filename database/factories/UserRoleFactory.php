<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'role_id' => Role::factory(),
        ];
    }
}
