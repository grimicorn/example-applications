<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingCompletionScoreTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_completion_score_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->unsigned();
            $table->integer('total')->unsigned()->nullable();
            $table->integer('historical_financials')->unsigned()->nullable();
            $table->integer('sources_of_income')->unsigned()->nullable();
            $table->integer('employee_related_expenses')->unsigned()->nullable();
            $table->integer('office_related_expenses')->unsigned()->nullable();
            $table->integer('selling_general_and_administrative_expenses')->unsigned()->nullable();
            $table->integer('finance_related_expenses')->unsigned()->nullable();
            $table->integer('other_cash_flow_items')->unsigned()->nullable();
            $table->integer('non_recurring_personal_or_extra_expenses')->unsigned()->nullable();
            $table->integer('balance_sheet_recurring_assets')->unsigned()->nullable();
            $table->integer('balance_sheet_long_term_assets')->unsigned()->nullable();
            $table->integer('balance_sheet_current_liabilities')->unsigned()->nullable();
            $table->integer('balance_sheet_long_term_liabilities')->unsigned()->nullable();
            $table->integer('balance_sheet_shareholders_equity')->unsigned()->nullable();
            $table->integer('business_overview')->unsigned()->nullable();
            $table->integer('basics')->unsigned()->nullable();
            $table->integer('more_about_the_business')->unsigned()->nullable();
            $table->integer('financial_details')->unsigned()->nullable();
            $table->integer('business_details')->unsigned()->nullable();
            $table->integer('transaction_considerations')->unsigned()->nullable();
            $table->integer('uploads')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('listing_id')->references('id')->on('listings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listing_completion_score_totals');
    }
}
