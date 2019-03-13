<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('applications', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('application_id');
        //     $table->integer('user_id')->unsigned();
        //     $table->integer('cluster_id')->unsigned();
        //     $table->integer('team_id')->unsigned();
        //     $table->string('received_date')->nullable();
        //     $table->string('encoded_date')->useCurrent();
        //     $table->string('customer_name');
        //     $table->string('device_name');
        //     $table->string('plan_applied');
        //     $table->string('product_type');
        //     $table->string('volume')->default(1);
        //     $table->string('msf');
        //     $table->string('saf_no');
        //     $table->string('codis_no');
        //     $table->string('sr_no');
        //     $table->string('so_no');
        //     $table->string('account_no');
        //     $table->string('mobile_no')->nullable();
        //     $table->string('iccid')->nullable();
        //     $table->string('imei')->nullable();
        //     $table->string('sales_source')->nullable();
        //     $table->string('agent_code')->nullable();
        //     $table->string('status');
        //     $table->string('status_remarks')->nullable();
        //     $table->string('document_remarks')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('applications');
    }
}
