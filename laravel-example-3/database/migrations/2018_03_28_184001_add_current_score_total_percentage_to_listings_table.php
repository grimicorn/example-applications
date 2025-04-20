<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentScoreTotalPercentageToListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->float('current_score_total_percentage')->nullable();
            $table->integer('current_score_total_percentage_for_display')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('current_score_total_percentage');
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('current_score_total_percentage_for_display');
        });
    }
}
