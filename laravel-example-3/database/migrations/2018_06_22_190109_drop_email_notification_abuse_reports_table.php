<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEmailNotificationAbuseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_notification_abuse_reports', function (Blueprint $table) {
            Schema::dropIfExists('email_notification_abuse_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_notification_abuse_reports', function (Blueprint $table) {
            //
        });
    }
}
