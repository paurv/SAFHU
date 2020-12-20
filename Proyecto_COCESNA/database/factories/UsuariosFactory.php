<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Usuarios;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

$factory->define(Usuarios::class, function (Faker $faker) {
    return [
        'email' => $faker->email(),
        'contrasena' => Crypt::encryptString('0000'),
    ];
});

/*
class Metodos
{
    Public static function randomPassword()
    {
        return $faker->word();
    }

    Public static function encryptPassword($datos)
    {
        return Crypt::encryptString($datos);
    }

    Public static function hashPassword($datos)
    {
        return Hash::make($datos);
    }
}*/