<?php

use Faker\Generator as Faker;

$factory->define(App\Teams::class, function (Faker $faker) use ($factory) {
    $user_id = $faker->randomElements(App\User::pluck('id'), $faker->numberBetween(1, 5));
    return [
        'team_name' => $faker->name,
        'team_id' => rand(1111, 9999),
        // 'cl_id' => $factory->create(App\User::class)->id,
        'tl_id' => $factory->create(App\User::class)->id,
        'agent_code' => $user_id,
    ];
});
