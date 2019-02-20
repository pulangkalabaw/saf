<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        //
        'product_id' => rand(1111, 9999),
        'product_name' => $faker->name,
    ];
});
