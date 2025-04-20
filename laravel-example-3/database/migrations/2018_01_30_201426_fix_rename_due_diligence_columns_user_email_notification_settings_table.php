<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRenameDueDiligenceColumnsUserEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_notification_settings', function (Blueprint $table) {
            if (Schema::hasColumn('user_email_notification_settings', 'enable_due_dillegence')) {
                $table->renameColumn('enable_due_dillegence', 'enable_due_diligence');
            }

            if (Schema::hasColumn('user_email_notification_settings', 'due_dillegence_digest')) {
                $table->renameColumn('due_dillegence_digest', 'due_diligence_digest');
            }
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
            if (Schema::hasColumn('user_email_notification_settings', 'enable_due_diligence')) {
                $table->renameColumn('enable_due_diligence', 'enable_due_dillegence');
            }
        });

        Schema::table('user_email_notification_settings', function (Blueprint $table) {
            if (Schema::hasColumn('user_email_notification_settings', 'due_diligence_digest')) {
                $table->renameColumn('due_diligence_digest', 'due_dillegence_digest');
            }
        });
    }
}
