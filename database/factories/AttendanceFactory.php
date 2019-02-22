<?php

use Faker\Generator as Faker;

$factory->define(App\Attendance::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(App\user::pluck('id')),
        'activities' => $faker->randomElement(['Blitz', 'Saturation']),
        'location' => $faker->address,
        'remarks' => $faker->text($maxNbChars = 20),
        'status' => $faker->numberBetween(0, 1),
    ];
});
