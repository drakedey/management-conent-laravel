<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branch_types')->insert([
            'name' => 'partner',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);

        DB::table('branch_types')->insert([
            'name' => 'branch',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);
    }
}
