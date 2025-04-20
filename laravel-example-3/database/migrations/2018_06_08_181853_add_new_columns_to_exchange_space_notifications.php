<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToExchangeSpaceNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_space_notifications', function (Blueprint $table) {
            $table->integer('removed_member_id')->unsigned()->nullable();
            $table->integer('requested_member_id')->unsigned()->nullable();
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
            $table->dropColumn('removed_member_id');
        });

        Schema::table('exchange_space_notifications', function (Blueprint $table) {
            $table->dropColumn('requested_member_id');
        });
    }
}
