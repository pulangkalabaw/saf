<?php

use Faker\Generator as Faker;

$factory->define(App\Statuses::class, function (Faker $faker) {
    return [
        //
        'status' => $faker->name,
    ];
});
