<?php

use Illuminate\Database\Seeder;

class Teams extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Teams::class, 10)->create();

    }
}
