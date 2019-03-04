<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oic', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cluster_id');
            $table->string('team_id');
            $table->string('user_id');
            $table->string('expired_at');
            $table->integer('insert_by');
            $table->string('assign_date');
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
        Schema::dropIfExists('oic');
    }
}
