<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(App\Gallery::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'description' => $faker->text(200),
        'user_id' =>  User::all()->random()->id

    ];
});
