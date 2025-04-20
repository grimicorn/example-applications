<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaleCompletedToListingExitSurveys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listing_exit_surveys', function (Blueprint $table) {
            $table->boolean('sale_completed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listing_exit_surveys', function (Blueprint $table) {
            $table->dropColumn('sale_completed');
        });
    }
}
