<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);

        DB::table('types_content')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'type_id' => 1,
            'language_id' => 1,
            'name' => 'Carro'
        ]);

        DB::table('types_content')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'type_id' => 1,
            'language_id' => 2,
            'name' => 'Car'
        ]);
    }
}
