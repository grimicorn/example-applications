<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbuseReportLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abuse_report_links', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message')->nullable();
            $table->integer('reporter_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('notification_type')->unsigned();
            $table->text('reason')->nullable();
            $table->text('reason_details')->nullable();
            $table->integer('message_id')->unsigned()->nullable();
            $table->string('message_model')->nullable();
            $table->timestamp('reported_on')->nullable();
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
        Schema::dropIfExists('abuse_report_links');
    }
}
