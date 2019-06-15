<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            'name' => 'english',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);

        DB::table('languages')->insert([
            'name' => 'spanish',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);
    }
}
