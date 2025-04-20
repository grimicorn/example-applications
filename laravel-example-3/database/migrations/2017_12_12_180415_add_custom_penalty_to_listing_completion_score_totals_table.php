<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomPenaltyToListingCompletionScoreTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listing_completion_score_totals', function (Blueprint $table) {
            $table->integer('custom_penalty')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listing_completion_score_totals', function (Blueprint $table) {
            $table->dropColumn('custom_penalty');
        });
    }
}
