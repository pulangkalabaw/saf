<?php

use Faker\Generator as Faker;

$factory->define(App\Application::class, function (Faker $faker) use ($factory) {
        //
    $s = $faker->dateTimeThisYear();

	return [
        'application_id' => str_random(16),
		'cluster_id' => $factory->create(App\Clusters::class)->id,
		'team_id' => $factory->create(App\Teams::class)->id,
		'customer_name' => $faker->name,
		'contact' => $faker->name,
		'address' => $faker->address,
		'plan_id' => $factory->create(App\Plans::class)->id,
		'sim' => rand(1111, 9999999),
		'device_id' => $factory->create(App\Devices::class)->id,
        'sr_no' => rand(111111111, 999999999),
        'so_no' => rand(111111111, 999999999),
		'agent_id' => $factory->create(App\User::class)->id, // agent
		'status' => 'new',
        'created_at' => $faker->dateTimeThisYear(),
		'updated_at' => $faker->dateTimeThisYear(),
		'encoder_id' => $factory->create(App\User::class)->id, // agent
		'insert_by' => $factory->create(App\User::class)->id, // agent
        'encoded_at' => $faker->dateTimeThisYear(),
    ];
});
