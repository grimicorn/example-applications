<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDueDillegenceDigestToUserEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_notification_settings', function (Blueprint $table) {
            $table->boolean('due_dillegence_digest')->default(true);
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
            $table->dropColumn('due_dillegence_digest');
        });
    }
}
