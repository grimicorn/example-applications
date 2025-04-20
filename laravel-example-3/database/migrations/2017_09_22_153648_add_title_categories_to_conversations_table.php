<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\ConversationCategoryType;

class AddTitleCategoriesToConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('conversations', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->enum('category', ConversationCategoryType::getValues())->default(ConversationCategoryType::OTHER);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            //
        });
    }
}
