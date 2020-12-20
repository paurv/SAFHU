<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Turno;
use Faker\Generator as Faker;

$factory->define(Turno::class, function (Faker $faker) {
    return [
        'turno' => $faker->word(),
        'hora_inicio' => $faker->time($format = 'H:i:s', $max = 'now'),
        'hora_fin' => $faker->time($format = 'H:i:s', $max = 'now'),
    ];
});
