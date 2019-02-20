<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('status');
            $table->integer('team_id')->unsigned();
            $table->integer('active');
            $table->integer('added_by')->unsigned();
            $table->timestamps();
        });


        Schema::table('application_status', function(Blueprint $table) {
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_status');
    }
}
