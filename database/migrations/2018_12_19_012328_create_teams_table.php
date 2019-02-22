<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('team_name');
            $table->string('team_id');
            // $table->integer('cl_id')->unsigned();
            $table->string('tl_ids')->nullable();
            $table->string('agent_ids')->nullable();
            $table->timestamps();
        });

        // Schema::table('teams', function(Blueprint $table) {
        //     $table->foreign('cl_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('tl_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('agent_code')->references('agent_code')->on('users')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
