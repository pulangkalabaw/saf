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
            // CREATE USER ADMIN
        User::create([
            'fname' => 'Ender Thompson',
            'lname' => 'Baril',
            'email' => 'lelouch0278@gmail.com',
            'password' => bcrypt('sadsad'),
            'role' => base64_encode('administrator'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        ]);
        // CLUSTER LEADER
        $user_cl = User::insertGetId([
            'fname' => 'Sarah Jane',
            'lname' => 'Sarasua',
            'email' => 'sarah@gmail.com',
            'password' => bcrypt('sadsad'),
            'role' => base64_encode('cl'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        ]);
        // TEAM LEADER
        $users_tl = array(
            'fname' => 'jiromes Angel',
            'lname' => 'Baril',
            'email' => 'jiromes@gmail.com',
            'password' => bcrypt('sadsad'),
            'role' => base64_encode('tl'),
            'isActive' => 1,
            'remember_token' => str_random(10),
        );
        $user_tl = User::insertGetId($users_tl);
        $user_agents = array(
            array(
                'fname' => 'Monica',
                'lname' => 'Baril',
                'email' => 'moncia@gmail.com',
                'password' => bcrypt('sadsad'),
                'role' => base64_encode('agent'),
                'isActive' => 1,
                'agent_code' => rand(1111, 9999),
                'remember_token' => str_random(10),
            ),
            array(
                'fname' => $faker->firstname,
                'lname' => $faker->lastname,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'role' => base64_encode('agent'),
                'isActive' => 1,
                'agent_code' => rand(1111, 9999),
                'remember_token' => str_random(10),
            ),
            array(
                'fname' => $faker->firstname,
                'lname' => $faker->lastname,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'role' => base64_encode('agent'),
                'isActive' => 1,
                'agent_code' => rand(1111, 9999),
                'remember_token' => str_random(10),
            )
        );
        User::insert($user_agents);

        // CLUSTER
        $users_teams = Teams::take(3)->orderBy('id', 'desc')->pluck('team_id');
        Clusters::create([
            'cluster_id' => rand(1111, 9999),
            'cluster_name' => 'Team m4pHaG_M4ha4L',
            'cl_id' => $user_cl,
            'team_ids' => $users_teams,
        ]);

        $user = User::where('role', base64_encode('agent'))->take(5)->orderBy('id', 'desc')->pluck('id');
        // TEAM SELECT
        Teams::create([
            'team_name' => 'Team HaIsZt_b3nteqUa4htRo0',
            'team_id' => rand(1111, 9999),
            'tl_id' => $user_tl,
            'agent_code' => $user,
        ]);
    }
}
