<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Personal;
use Faker\Generator as Faker;

$factory->define(Personal::class, function (Faker $faker) {
    return [
        'nombres' => $faker->word." ".$faker->word,
        'apellidos' => $faker->word." ".$faker->word,
        'fecha_nacimiento' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'fecha_ingreso' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'sexo' => rand(1,2),
        'no_empleado' => $faker->unique()->randomNumber($nbDigits = 3),
    ];
});


