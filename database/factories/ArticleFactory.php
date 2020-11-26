<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'content' => $faker->realText($faker->numberBetween(50, 1000)),
        'is_restricted' => $faker->boolean,
        'role_id' => $faker->numberBetween(1, 3),
    ];
});
