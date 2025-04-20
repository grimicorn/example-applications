<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetEnableLoginDefaultFalseToUserEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_notification_settings', function (Blueprint $table) {
            $table->boolean('enable_login')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_email_notification_settings', function (Blueprint $table) {
            $table->boolean('enable_login')->default(true)->change();
        });
    }
}
