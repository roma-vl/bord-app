<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Генеруємо 100 випадкових користувачів
        User::factory(100)->create();

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
