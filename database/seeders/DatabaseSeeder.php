<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserRoleSeeder::class,
        ]);

        User::factory(10)->create();

        // Додаємо тестового користувача (гарантовано активного)
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//            'email_verified_at' => now(),
//            'avatar' => 'https://via.placeholder.com/100', // Статичний аватар для тестового юзера
//            'locale' => 'en',
//            'deleted_at' => null, // Не видаляємо
//        ]);
    }
}
