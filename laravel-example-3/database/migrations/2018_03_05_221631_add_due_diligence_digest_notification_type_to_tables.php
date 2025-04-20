<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\Notification\NotificationType;

class AddDueDiligenceDigestNotificationTypeToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        NotificationType::updateTableColumns();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        NotificationType::updateTableColumns();
    }
}
