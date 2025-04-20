<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameHistoricalFinancialIdToListingIdOnRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenues', function (Blueprint $table) {
            if (Schema::hasColumn('revenues', 'historical_financial_id')) {
                $table->dropForeign('revenues_historical_financial_id_foreign');
                $table->renameColumn('historical_financial_id', 'listing_id');
                $table->foreign('listing_id')->references('id')->on('listings');
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
        Schema::table('revenues', function (Blueprint $table) {
            if (Schema::hasColumn('revenues', 'listing_id')) {
                $table->dropForeign('revenues_listing_id_foreign');
                $table->renameColumn('listing_id', 'historical_financial_id');
                $table->foreign('historical_financial_id')->references('id')->on('historical_financials');
            }
        });
    }
}
