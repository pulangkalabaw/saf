<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Application::class,
            // Statuses::class,
            // Product::class,
            // Devices::class,
            // Plans::class,
            User::class,
            Teams::class,
			Clusters::class,
            // AttendanceSeeder::class,
            JiromesPersonalSeeder::class,
        ]);
    }
}
