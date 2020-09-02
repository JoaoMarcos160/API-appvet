<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Usuarios;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Usuarios::class, function (Faker $faker) {
    return [
        'nome'  => $faker->name(),
        'login' => $faker->email,
        'senha' => Hash::make($faker->password(6, 30)),
        'permissao' => $faker->numberBetween(1, 3)
    ];
});
