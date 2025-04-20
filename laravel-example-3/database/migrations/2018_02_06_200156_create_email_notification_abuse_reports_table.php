<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailNotificationAbuseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_notification_abuse_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->integer('notification_type')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('reporter_id')->unsigned();
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('reporter_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_notification_abuse_reports');
    }
}
