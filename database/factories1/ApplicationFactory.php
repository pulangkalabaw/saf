<?php

use Faker\Generator as Faker;

$factory->define(App\Application::class, function (Faker $faker) use ($factory) {
        //
    $s = $faker->dateTimeThisYear();
    return [
        'application_id' => str_random(16),
        'received_date' => $faker->dateTimeThisYear(),
        'user_id' => $factory->create(App\User::class)->id,
        'cluster_id' => $factory->create(App\Clusters::class)->cluster_id,
        'team_id' => $factory->create(App\Teams::class)->team_id,
        'customer_name' => $faker->name,
        'device_name' => $faker->name,
        'plan_applied' => 'PLAN 123',
        'product_type' => "SUN",
        'msf' => rand(111, 999),
        'saf_no' => rand(111111111, 999999999),
        'codis_no' => rand(111111111, 999999999),
        'sr_no' => rand(111111111, 999999999),
        'so_no' => rand(111111111, 999999999),
        'account_no' => rand(111111111, 999999999),
        'status' => '-',
        'encoded_date' => $s,
        'agent_code' => $factory->create(App\User::class)->agent_code,
    ];
});
