<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyCascadeToSavedSearchNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_search_notifications', function (Blueprint $table) {
            // User id
            $table->dropForeign('saved_search_notifications_user_id_foreign');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            // Saved search id
            $table->dropForeign('saved_search_notifications_saved_search_id_foreign');
            $table->foreign('saved_search_id')
            ->references('id')
            ->on('saved_searches')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_search_notifications', function (Blueprint $table) {
            // User id
            $table->dropForeign('saved_search_notifications_user_id_foreign');
            $table->foreign('user_id')
            ->references('id')
            ->on('users');

            // Saved search id
            $table->dropForeign('saved_search_notifications_saved_search_id_foreign');
            $table->foreign('saved_search_id')
            ->references('id')
            ->on('saved_searches');
        });
    }
}
