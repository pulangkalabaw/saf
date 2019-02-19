<?php

use Faker\Generator as Faker;

$factory->define(App\Devices::class, function (Faker $faker) {
    return [
        //
        'device_id' => rand(1111, 9999),
        'device_name' => $faker->name,
    ];
});
