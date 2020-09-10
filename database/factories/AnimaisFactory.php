<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Animais;
use Faker\Generator as Faker;

$factory->define(Animais::class, function (Faker $faker) {
    return [
        'cliente_id' => 1,
        'nome_animal' => $faker->firstName,
        'dt_nasc' => $faker->boolean(60) ? $faker->date() : null,
        'observacao' =>  $faker->boolean(35) ? $faker->text() : null,
        'microchip' => $faker->boolean(10) ? $faker->asciify('********************') : null,
        'tag' => $faker->boolean(10) ? $faker->asciify('************') : null,
        'sexo' => $faker->boolean() ? 'M' : 'F',
        'castrado' => $faker->boolean(),
        'cor' => $faker->boolean() ? $faker->colorName : null,
        'caminho_foto' => $faker->boolean() ? $faker->imageUrl() : null,
    ];
});
