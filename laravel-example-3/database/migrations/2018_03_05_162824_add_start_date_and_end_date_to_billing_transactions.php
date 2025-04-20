<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateAndEndDateToBillingTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_transactions', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_transactions', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });

        Schema::table('billing_transactions', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
}
