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

        factory(Country::class, 3)->create();
    }
}
