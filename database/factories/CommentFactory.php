<?php

use Faker\Generator as Faker;
use App\User;
use App\Gallery;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->text(300),
        'user_id' => User::all()->random()->id,
        'gallery_id' => Gallery::all()->random()->id
    ];
});
