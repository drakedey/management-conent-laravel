<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Country;
use App\User;
use Faker\Generator as Faker;


$factory->define(Country::class, function (Faker $faker) {
   return [
       'name' => $faker->word,
       'uri_param' => 'cl',
       'user_id' => User::all()->random()->id
   ];


});
