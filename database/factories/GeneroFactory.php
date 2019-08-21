<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Genero;
use Faker\Generator as Faker;

$factory->define(Genero::class, function (Faker $faker) {
    return [
        'nome' => $faker->colorName
    ];
});
