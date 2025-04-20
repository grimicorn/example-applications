<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSavedSearchesListingUpdatedAtFromEnumToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_searches', function (Blueprint $table) {
            $table->dropColumn('listing_updated_at');
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            $table->text('listing_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
