<?php

use Faker\Generator as Faker;
use App\Teams;
use App\User;

$factory->define(App\Teams::class, function (Faker $faker) use ($factory) {
    $tl_ids = [];
    $agent_ids = [];
    // CHECK IF TEAM TABLE IS EMPTY
    // if(Teams::all()->count() > 0){ // IF IT'S NOT EMPTY WE NEED DO ALL THE USERS THAT DOESN'T BELONG TO TEAMS TL_IDS OR AGENT_IDS
        $get_teams = [];
        foreach(Teams::get() as $team){
            foreach($team['agent_ids'] as $agent){
                array_push($get_teams, $agent); // GET ALL AGENTS
            }
            foreach($team['tl_ids'] as $tls){
                array_push($get_teams, $tls); // GET ALL TLS
            }
        }
        $get_users = [];
        foreach(User::get() as $user){
            if(!in_array($user['id'], $get_teams)){
                // IF THE USER IS ALREADY EXISTING ON TEAMS TABLE, THEN DON'T GET IT
                array_push($get_users, $user['id']);
            }
        }
        $tl_ids = $faker->randomElements($get_users, 1); // GET RANDOM USER ID FOR TL_IDS
        $agent_ids = $faker->randomElements($get_users, $faker->numberBetween(1, 3)); // GET RANDOM USER ID FOR AGENT_IDS
    // } else {
    //     $users = User::orderBy('id', 'desc')->pluck('id');
    //     $tl_ids = $faker->randomElements($users, 1);
    //     $agent_ids = $faker->randomElements($users, $faker->numberBetween(1, 3));
    // }
    return [
        'team_name' => $faker->name,
        'team_id' => rand(1111, 9999),
        'tl_ids' => $tl_ids,
        'agent_ids' => $agent_ids,
    ];
});
