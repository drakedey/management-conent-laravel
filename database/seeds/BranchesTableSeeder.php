<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            'name' => 'disrupt',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1,
            'branch_type_id' => 1,
        ]);

        DB::table('images')->insert([
            'name' => 'disrupt.png',
            'type' => 'branch',
            'url' => 'http://127.0.0.1:8000/images/branch/disrupt.png',
            'created_at' => now(),
            'updated_at' => now(),
            'branch_id' => 1,
            'product_id' => null,
            'new_id' => null
        ]);

        DB::table('branches')->insert([
            'name' => 'microsoft',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1,
            'branch_type_id' => 2,
        ]);

        DB::table('images')->insert([
            'name' => 'microsoft.png',
            'type' => 'branch',
            'url' => 'http://127.0.0.1:8000/images/branch/microsoft.png',
            'created_at' => now(),
            'updated_at' => now(),
            'branch_id' => 2,
            'product_id' => null,
            'new_id' => null
        ]);

        DB::table('branches')->insert([
            'name' => 'apple',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1,
            'branch_type_id' => 1,
        ]);

        DB::table('images')->insert([
            'name' => 'apple.png',
            'type' => 'branch',
            'url' => 'http://127.0.0.1:8000/images/branch/apple.png',
            'created_at' => now(),
            'updated_at' => now(),
            'branch_id' => 3,
            'product_id' => null,
            'new_id' => null
        ]);
    }
}
