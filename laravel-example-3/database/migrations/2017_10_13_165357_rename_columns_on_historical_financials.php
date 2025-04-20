<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnsOnHistoricalFinancials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historical_financials', function (Blueprint $table) {
            if (Schema::hasColumn('historical_financials', 'preferred_stock')) {
                $table->renameColumn('preferred_stock', 'retained_earnings');
            }

            if (Schema::hasColumn('historical_financials', 'treasury_stock')) {
                $table->renameColumn('treasury_stock', 'other_equity_accounts');
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
        Schema::table('historical_financials', function (Blueprint $table) {
            //
        });
    }
}
