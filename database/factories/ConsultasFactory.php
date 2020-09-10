<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Consultas;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Consultas::class, function (Faker $faker) {
    return [
        'animal_id'  => 1,
        'observacao' => $faker->boolean(35) ? $faker->text : null,
        'doenca' => $faker->boolean(80) ? $faker->lastName : null,
        'recomendacao' => $faker->boolean(70) ? $faker->text(50) : null,
        'valor_cobrado' => $faker->numberBetween(5, 1000),
    ];
});
