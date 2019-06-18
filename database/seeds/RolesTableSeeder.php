<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use App\Rol;
class RolesTableSeeder extends Seeder {

    public function run() {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Rol::query()->truncate();

        DB::table('roles')->insert([
            'name' => 'ADMIN',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('roles')->insert([
            'name' => 'USER',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

}

