<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name'=> $faker->text(20),
        'qty'=> $faker->text(5),
        'price'=> $faker->text(10)
    ];
});
