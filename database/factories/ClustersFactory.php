<?php

use Faker\Generator as Faker;

$factory->define(App\Clusters::class, function (Faker $faker) use ($factory) {
    return [
        //
        'cluster_id' => rand(1111, 9999),
        'cluster_name' => $faker->name,
        'cl_id' => $factory->create(App\User::class)->id,
        'team_ids' => json_encode([$factory->create(App\Teams::class)->team_id]),
    ];
});