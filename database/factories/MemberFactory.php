<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Member;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Member::class, function (Faker $faker) {
    return [
        'member_number' => $faker->regexify('[a-z]{2}[0-9]{3}\-[0-9]{8}'),
        'last_name'     => $faker->lastName,
        'first_name'    => $faker->firstName,
        'sex'           => $faker->numberBetween($min = 1, $max = 2),
        'birthday'      => $faker->date($format = 'Y-m-d', $max = '2020-01-01'),
        'email'         => $faker->unique()->safeEmail,
        'dept_id'       => $faker->numberBetween($min = 1, $max = 5),
        'tel'           => $faker->regexify('[0-9]{3}\-[0-9]{4}\-[0-9]{4}'),
        'join_date'     => $faker->date($format = 'Y-m-d', $max = 'now'),
        'memo'          => $faker->sentence(6,  true)
    ];
});
