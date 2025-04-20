<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlogPostsToUserEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_notification_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('user_email_notification_settings', 'blog_posts')) {
                $table->boolean('blog_posts')->nullable();
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
            //
        });
    }
}
