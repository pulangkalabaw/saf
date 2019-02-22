<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $check_jiromes_email = App\User::where('email', 'jiromes@gmail.com')->first();
    if($check_jiromes_email == null){
        App\User::create([
            'fname' => 'jiromes Angel',
            'lname' => 'Baril',
            'email' => 'jiromes@gmail.com',
            'password' => bcrypt('sadsad'),
            'role' => base64_encode('tl'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        ]);
    }

    $check_khurt_email = App\User::where('email', 'kllopez@iplusonline.com')->first();
    if($check_khurt_email == null){
        App\User::create([
            'fname' => $faker->firstname,
            'lname' => $faker->lastname,
            'email' => 'kllopez@iplusonline.com',
            'password' => bcrypt('secret'),
            'role' => base64_encode('administrator'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        ]);
    }

    static $password;
    $role = ['cl', 'tl', 'encoder', 'agent', 'agent_referral'];
    $role_arr = array_random_assoc($role);
    $role = array_values($role_arr)[0];

    return [
        'fname' => $faker->firstname,
        'lname' => $faker->lastname,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'role' => base64_encode($role),
        'isActive' => 1,
        'agent_code' => ($role == 'agent' || $role == 'agent_referral') ? rand(1111, 9999) : '',
        'remember_token' => str_random(10),
        // 'pat' => ($role == 'agent' || $role == 'agent_referral') ? rand(1111, 9999) : '',
    ];
});
