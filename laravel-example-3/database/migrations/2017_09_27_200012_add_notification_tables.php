<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\Notification\NotificationType;

class AddNotificationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_search_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('saved_search_id');
            $table->enum('type', NotificationType::getConstants()->toArray())->default(NotificationType::SAVED_SEARCH_MATCH);
            $table->string('rule_name');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('saved_search_id')->references('id')->on('saved_searches');
        });

        Schema::create('exchange_space_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('exchange_space_id');
            $table->enum('type', NotificationType::getConstants()->toArray());
            $table->string('exchange_space_title')->nullable();
            $table->string('exchange_space_deal')->nullable();
            $table->string('exchange_space_status')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('requested_member_name')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('exchange_space_id')->references('id')->on('exchange_spaces');
        });

        Schema::create('conversation_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('conversation_id');
            $table->enum('type', NotificationType::getConstants()->toArray())->default(NotificationType::MESSAGE);
            $table->string('message_sender_name')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('conversation_id')->references('id')->on('conversations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
