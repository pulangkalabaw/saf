<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cluster_id');
            $table->string('cluster_name');
            $table->integer('cl_id')->unsigned(); // cluster leader
            $table->string('team_ids', 255); // json format
            $table->timestamps();
        });

        Schema::table('clusters', function(Blueprint $table) {
            $table->foreign('cl_id')->references('id')->on('users')->onDelete('cascade');
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clusters');
    }
}
