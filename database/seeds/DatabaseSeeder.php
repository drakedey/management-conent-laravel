<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(ProductTypeTableSeeder::class);
        $this->call(BranchTypeSeeder::class);
        $this->call(BranchesTableSeeder::class);
    }
}
