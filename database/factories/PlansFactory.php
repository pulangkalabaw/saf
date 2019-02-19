<?php

use Faker\Generator as Faker;

$factory->define(App\Plans::class, function (Faker $faker) {
    return [
        //
        'plan_id' => rand(1111, 9999),
        'plan_name' => $faker->name,
    ];
});
