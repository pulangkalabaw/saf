<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cluster_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('activities');
            $table->string('location');
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('created_by');
            $table->string('modified_by')->nullable();
            $table->string('modified_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
