<?php

use Faker\Generator as Faker;

$factory->define(App\Attendance::class, function (Faker $faker) {
    $cluster_id = $faker->randomElement(App\Clusters::get());
    // return $cluster_id['team_ids'];
    $team_id  = App\Teams::where('id', $faker->randomElement($cluster_id['team_ids']))->first();
    $user_id = App\User::where('id', $faker->randomElement($team_id['agent_ids']))->value('id');
    // $cluster_id = App\Clusters::where('team_ids', 'like', '%' . $team_id['team_id'] . '%')->value('id');
    return [
        'cluster_id' => $cluster_id['id'],
        'team_id' => $team_id,
        'user_id' => $user_id,
        'activities' => $faker->randomElement(['Blitz', 'Saturation']),
        'location' => $faker->address,
        'remarks' => $faker->text($maxNbChars = 20),
        'status' => $faker->numberBetween(0, 1),
    ];
});
