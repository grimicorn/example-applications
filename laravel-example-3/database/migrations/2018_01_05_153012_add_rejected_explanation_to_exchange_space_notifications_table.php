<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectedExplanationToExchangeSpaceNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_space_notifications', function (Blueprint $table) {
            $table->text('rejected_explanation')->nullable();
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
            $table->dropColumn('rejected_explanation');
        });
    }
}
