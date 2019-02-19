<?php

use Illuminate\Database\Seeder;

class Application extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Application::class, 9)->create();

    }
}
