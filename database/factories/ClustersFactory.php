<?php

use Faker\Generator as Faker;
use App\Teams;
use App\Clusters;
use App\User;

$factory->define(App\Clusters::class, function (Faker $faker) use ($factory) {
    $get_clusters = [];
    foreach(Teams::get() as $team){
        foreach($team['agent_ids'] as $agent){
            array_push($get_clusters, $agent); // GET ALL AGENTS
        }
        foreach($team['tl_ids'] as $tls){
            array_push($get_clusters, $tls); // GET ALL TLS
        }
    }


    $get_clusters = [];
    foreach(Clusters::get() as $team){
        foreach($team['team_ids'] as $agent){
            array_push($get_clusters, $agent); // GET ALL AGENTS
        }
        foreach($team['cl_ids'] as $tls){
            array_push($get_clusters, $tls); // GET ALL TLS
        }
    }


    $get_users = [];
    foreach(User::get() as $user){
        if(!in_array($user['id'], $get_clusters)){
            // IF THE USER IS ALREADY EXISTING ON TEAMS TABLE, THEN DON'T GET IT
            // $get_users[] = $user['id'];
            array_push($get_users, $user['id']);
        }
    }
    $cl_ids = $faker->randomElements((array)$get_users, 2); // GET RANDOM USER ID FOR TL_IDS
    $team_ids = $faker->randomElements(Teams::pluck('id'), $faker->numberBetween(1, 2)); // GET RANDOM USER ID FOR AGENT_IDS


    return [
        'cluster_id' => rand(1111, 9999),
        'cluster_name' => $faker->name,
        'cl_ids' => $cl_ids,
        'team_ids' => $team_ids,
    ];
});
