<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinancialAccessControlToExchangeSpaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_spaces', function (Blueprint $table) {
            $table->boolean('historical_financials_public')->default(false);
            $table->boolean('adjusted_financials_trends_public')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_spaces', function (Blueprint $table) {
            //
        });
    }
}
