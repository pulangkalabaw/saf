<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('saf_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_id');
            $table->string('cluster_id'); // Cluster id
            $table->string('team_id'); // Team id
			$table->string('customer_name');
            $table->string('product');
			$table->string('contact');
			$table->string('address');
			$table->integer('plan_id')->unsigned();
			$table->float('msf');
			$table->string('sim')->nullable();
			$table->string('device_id')->nullable();
			$table->integer('agent_id')->unsigned(); // User.id (Agent id)
			$table->string('sr_no')->nullable();
			$table->string('so_no')->nullable();
			$table->string('status');
            $table->string('awaiting_device')->nullable();
            $table->string('expires_at')->nullable();
			$table->integer('insert_by')->unsigned()->nullable();
			$table->integer('encoder_id')->unsigned()->nullable();
			$table->string('encoded_at');
            $table->timestamps();
        });

		Schema::table('saf_applications', function(Blueprint $table) {
			$table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
			$table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('encoder_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('insert_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saf_application');
    }
}
