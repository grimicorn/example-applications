<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropMatchedIdsSavedSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_searches', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            if (Schema::hasColumn('saved_searches', 'matched_ids')) {
                $table->dropColumn('matched_ids');
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
        Schema::table('saved_searches', function (Blueprint $table) {
            //
        });
    }
}
