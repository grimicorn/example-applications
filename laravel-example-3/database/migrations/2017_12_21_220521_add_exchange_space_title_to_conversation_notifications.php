<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExchangeSpaceTitleToConversationNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversation_notifications', function (Blueprint $table) {
            $table->string('exchange_space_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversation_notifications', function (Blueprint $table) {
            $table->dropColumn('exchange_space_title');
        });
    }
}
