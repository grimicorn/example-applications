<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\Notification\NotificationType;

class AddNotificationTypesToNotificationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_space_notifications', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $types = collect(NotificationType::getConstants())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE exchange_space_notifications CHANGE type type ENUM({$types})");
        });

        Schema::table('conversation_notifications', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            $types = collect(NotificationType::getConstants())->map(function ($value) {
                return "'{$value}'";
            })->implode(',');
            DB::statement("ALTER TABLE conversation_notifications CHANGE type type ENUM({$types})");
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
