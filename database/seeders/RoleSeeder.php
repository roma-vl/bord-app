<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'is_enabled' => true],
            ['name' => 'Editor', 'is_enabled' => true],
            ['name' => 'User', 'is_enabled' => true],
        ]);
    }
}
