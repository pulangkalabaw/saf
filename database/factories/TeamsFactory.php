<?php

use Faker\Generator as Faker;

$factory->define(App\Teams::class, function (Faker $faker) use ($factory) {
    return [
        //
        'team_name' => $faker->name,
        'team_id' => rand(1111, 9999),
        // 'cl_ids' => [$factory->create(App\User::class)->id],
        'tl_ids' => [$factory->create(App\User::class)->id],
        'agent_ids' => [$factory->create(App\User::class)->agent_code],
    ];
});
