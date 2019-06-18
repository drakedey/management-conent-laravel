<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use App\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Country::query()->truncate();

        DB::table('countries')->insert([
            'name' => 'chile',
            'uri_param' => 'cl',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('countries')->insert([
            'name' => 'peru',
            'uri_param' => 'pe',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('countries')->insert([
            'name' => 'paraguay',
            'uri_param' => 'pl',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
