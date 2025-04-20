<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePhaseTwoExchangeSapceSavedSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_searches', function (Blueprint $table) {
            if (!app()->environment('testing')) {
                $table->dropColumn('es_documents');
                $table->dropColumn('es_business_plan');
                $table->dropColumn('es_historical_financials');
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
            // $table->boolean('es_documents')->default(false);
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            // $table->boolean('es_business_plan')->default(false);
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            // $table->boolean('es_historical_financials')->default(false);
        });
    }
}
