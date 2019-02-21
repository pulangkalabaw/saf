<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_board', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cluster_id')->nullable();
            $table->string('team_id')->nullable();
            $table->integer('posted_by')->unsigned();
            $table->tinyInteger('pinned')->default(0);
            $table->text('subject');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('msg_board', function(Blueprint $table) {
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('msg_board');
    }
}
