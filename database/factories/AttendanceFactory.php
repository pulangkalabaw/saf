<?php

use Faker\Generator as Faker;

$factory->define(App\Attendance::class, function (Faker $faker) {
    // $user_id = $faker->randomElement(App\user::pluck('id'));
    $cluster_id = App\Clusters::inRandomOrder()->take(1)->first();
    $team_leader_id = $faker->randomElement($cluster_id['team_ids']);
    $teams_id = App\Teams::where('team_id', $team_leader_id)->inRandomOrder()->take(1)->first();
    $user_id = $faker->randomElement($teams_id['agent_code']);

    // $check_if_existing = App\Attendance::where('user_id', $user_id)->get();
    // if(empty($check_if_existing)){
        return [
            'cluster_id' => $cluster_id,
            'team_id' => $teams_id,
            'user_id' => $user_id,
            'activities' => $faker->randomElement(['Blitz', 'Saturation']),
            'location' => $faker->address,
            'remarks' => $faker->text($maxNbChars = 20),
            'status' => $faker->numberBetween(0, 1),
            'created_by' => $teams_id,
        ];
    // }
});
