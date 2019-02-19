<?php

use Illuminate\Database\Seeder;

class Plans extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Plans::class, 1)->create();

    }
}
