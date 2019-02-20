<?php

use Illuminate\Database\Seeder;

class Devices extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Devices::class, 5)->create();

    }
}
