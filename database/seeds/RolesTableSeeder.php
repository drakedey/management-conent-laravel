<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
class RolesTableSeeder extends Seeder {

    public function run() {
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

