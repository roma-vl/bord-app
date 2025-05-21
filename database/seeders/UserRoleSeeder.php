<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::where('name', 'Admin')->first();
        $editor = Role::where('name', 'Editor')->first();
        $user = Role::where('name', 'User')->first();

        $user1 = User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::firstOrCreate(['email' => 'editor@example.com'], [
            'name' => 'Editor User',
            'password' => bcrypt('password'),
        ]);

        $user1->roles()->sync([$admin->id, $editor->id]);
        $user2->roles()->sync([$editor->id, $user->id]);
    }
}
