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

    static $password;
    $role = ['cl', 'tl', 'encoder', 'agent'];
    // $role = ['administrator'];
    $role_arr = array_random_assoc($role);
    $role = array_values($role_arr)[0];

    return [
        'fname' => $faker->firstname,
        'lname' => $faker->lastname,
        'email' => $faker->unique()->safeEmail,
        // 'email' => 'kllopez@iplusonline.com',
        'password' => $password ?: $password = bcrypt('secret'),
        'role' => base64_encode($role),
        'isActive' => 1,
        'agent_code' => ($role == 'agent') ? rand(1111, 9999) : '',
        'remember_token' => str_random(10),
    ];
});
