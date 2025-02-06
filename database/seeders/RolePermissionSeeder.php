<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_permissions')->insert([
            ['role_id' => 1, 'object' => 'user', 'operation' => 'create'],
            ['role_id' => 1, 'object' => 'user', 'operation' => 'delete'],
            ['role_id' => 2, 'object' => 'user', 'operation' => 'edit'],
            ['role_id' => 3, 'object' => 'user', 'operation' => 'view'],
        ]);
    }
}
