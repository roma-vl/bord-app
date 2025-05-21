<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'user.create', 'user.edit', 'user.delete', 'user.view',
            'post.create', 'post.edit', 'post.delete', 'post.view',
            'category.create', 'category.edit', 'category.delete', 'category.view',
        ];

        foreach ($permissions as $key) {
            Permission::create(['key' => $key, 'description' => ucfirst(str_replace('.', ' ', $key))]);
        }
    }
}
