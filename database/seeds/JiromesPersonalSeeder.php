<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
Use App\User;
use App\Teams;
use App\Clusters;
class JiromesPersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=faker::create();

        User::create([
            'fname' => 'jiromes Angel',
            'lname' => 'Baril',
            'email' => 'jiromes@gmail.com',
            'password' => bcrypt('sadsad'),
            'role' => base64_encode('user'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        ]);
            // CREATE USER ADMIN
        // CLUSTER LEADER
        $user_cl = User::insertGetId([
            'fname' => 'Sarah Jane',
            'lname' => 'Sarasua',
            'email' => 'sarah@gmail.com',
            'password' => bcrypt('sadsad'),
            'role' => base64_encode('user'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        ]);
        // TEAM LEADER
        $user_tl = User::where('email', 'jiromes@gmail.com')->value('id');
        $user_agents = array(
            array(
                'fname' => 'Monica',
                'lname' => 'Baril',
                'email' => 'moncia@gmail.com',
                'password' => bcrypt('sadsad'),
                'role' => base64_encode('user'),
                'isActive' => 1,
                'agent_code' => rand(1111, 9999),
                'remember_token' => str_random(10),
            ),
            array(
                'fname' => $faker->firstname,
                'lname' => $faker->lastname,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'role' => base64_encode('user'),
                'isActive' => 1,
                'agent_code' => rand(1111, 9999),
                'remember_token' => str_random(10),
            ),
            array(
                'fname' => $faker->firstname,
                'lname' => $faker->lastname,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'role' => base64_encode('user'),
                'isActive' => 1,
                'agent_code' => rand(1111, 9999),
                'remember_token' => str_random(10),
            )
        );
        User::insert($user_agents);


        $user = User::where('role', base64_encode('user'))->take(5)->orderBy('id', 'desc')->pluck('id');
        // TEAM SELECT
        Teams::create([
            'team_name' => 'Team HaIsZt_b3nteqUa4htRo0',
            'team_id' => rand(1111, 9999),
            'tl_ids' => [$user_tl],
            'agent_ids' => $user,
        ]);

        // CLUSTER
        $users_teams = Teams::take(3)->orderBy('id', 'desc')->pluck('id');
        Clusters::create([
            'cluster_id' => rand(1111, 9999),
            'cluster_name' => 'Team m4pHaG_M4ha4L',
            'cl_ids' => [$user_cl],
            'team_ids' => $users_teams,
        ]);
    }
}
