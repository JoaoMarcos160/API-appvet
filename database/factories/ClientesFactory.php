<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Clientes;
use Faker\Generator as Faker;

$factory->define(Clientes::class, function (Faker $faker) {
    return [
        //'id', o próprio banco cria
        'usuario_id' => 1,
        'nome' => $faker->name(),
        'cpf' => $faker->boolean(70) ? $faker->numerify("###########") : null,
        'telefone' => $faker->phoneNumber,
        'endereco' => $faker->boolean(60) ? $faker->streetAddress : null,
        'numero' => $faker->boolean(60) ? $faker->numberBetween(1, 2000) : null,
        'bairro' => $faker->boolean(60) ? $faker->city : null,
        'cidade' => $faker->boolean(60) ? $faker->city : null,
        'estado' => $faker->boolean(60) ? $faker->state : null,
        'cep' => $faker->boolean(20) ? $faker->randomNumber(8) : null,
        'dt_nasc' => $faker->boolean(35) ? $faker->date() : null,
        'observacao' => $faker->boolean(35) ? $faker->text : null,
        'email' => $faker->boolean() ? $faker->safeEmail : null,
    ];
});
