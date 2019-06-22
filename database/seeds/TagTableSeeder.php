<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);

        DB::table('tags_content')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'tag_id' => 1,
            'language_id' => 1,
            'name' => 'Desportivo'
        ]);

        DB::table('tags_content')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'tag_id' => 1,
            'language_id' => 2,
            'name' => 'Deportive'
        ]);

        DB::table('tags')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);

        DB::table('tags_content')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'tag_id' => 2,
            'language_id' => 1,
            'name' => 'Lujo'
        ]);

        DB::table('tags_content')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'tag_id' => 2,
            'language_id' => 2,
            'name' => 'Luxury'
        ]);
    }
}
