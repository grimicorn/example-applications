<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadToNotificationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_space_notifications', function (Blueprint $table) {
            $table->boolean('read')->default(false);
        });

        Schema::table('conversation_notifications', function (Blueprint $table) {
            $table->boolean('read')->default(false);
        });

        Schema::table('saved_search_notifications', function (Blueprint $table) {
            $table->boolean('read')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_space_notifications', function (Blueprint $table) {
            //
        });
    }
}
