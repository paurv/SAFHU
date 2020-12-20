<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Posicion;
use Faker\Generator as Faker;

$factory->define(Posicion::class, function (Faker $faker) {
    return [
        'posicion' => $faker->word(),
    ];
});
