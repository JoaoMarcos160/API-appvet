<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Usuarios;
use Faker\Generator as Faker;

$factory->define(Usuarios::class, function (Faker $faker) {
    return [
        'nome'  => $faker->name(),
        'login' => $faker->email,
        'senha' => $faker->password(6, 50),
        'permissao' => $faker->numberBetween(1, 3)
    ];
});
