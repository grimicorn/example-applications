<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameHistoricalFinancialIdToListingIdOnExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'historical_financial_id')) {
                $table->dropForeign('expenses_historical_financial_id_foreign');
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
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'listing_id')) {
                $table->dropForeign('expenses_listing_id_foreign');
                $table->renameColumn('listing_id', 'historical_financial_id');
                $table->foreign('historical_financial_id')->references('id')->on('historical_financials');
            }
        });
    }
}
