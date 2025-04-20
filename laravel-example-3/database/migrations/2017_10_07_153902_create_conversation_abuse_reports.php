<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationAbuseReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_abuse_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->text('reason');
            $table->text('reason_details')->nullable();
            $table->integer('message_id')->unsigned();
            $table->integer('reporter_id')->unsigned();
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messages');
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
        Schema::dropIfExists('conversation_abuse_reports');
    }
}
