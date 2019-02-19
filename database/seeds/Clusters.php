<?php

use Illuminate\Database\Seeder;

class Clusters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Clusters::class, 10)->create();

    }
}
