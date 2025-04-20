<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_financials', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('listing_id');
            $table->date('year');
            $table->integer('number_transactions');
            $table->integer('number_customers');
            $table->decimal('employee_wages_benefits', 8, 2);
            $table->decimal('employee_education_training', 8, 2);
            $table->decimal('contractor_expenses', 8, 2);
            $table->decimal('utilities', 8, 2);
            $table->decimal('rent_lease_expenses', 8, 2);
            $table->decimal('office_supplies', 8, 2);
            $table->decimal('cost_goods_sold', 8, 2);
            $table->decimal('transportation', 8, 2);
            $table->decimal('meals_entertainment', 8, 2);
            $table->decimal('travel_expenses', 8, 2);
            $table->decimal('professional_services', 8, 2);
            $table->decimal('interest_expense', 8, 2);
            $table->decimal('depreciation', 8, 2);
            $table->decimal('amortization', 8, 2);
            $table->decimal('general_operational_expenses', 8, 2);
            $table->decimal('business_taxes', 8, 2);
            $table->decimal('capital_expenditures', 8, 2);
            $table->decimal('stock_based_compensation', 8, 2);
            $table->decimal('change_working_capital', 8, 2);
            $table->decimal('cash_equivalents', 8, 2);
            $table->decimal('investments', 8, 2);
            $table->decimal('accounts_receivable', 8, 2);
            $table->decimal('inventory', 8, 2);
            $table->decimal('prepaid_expenses', 8, 2);
            $table->decimal('other_current_assets', 8, 2);
            $table->decimal('property_plant_equipment', 8, 2);
            $table->decimal('goodwill', 8, 2);
            $table->decimal('intangible_assets', 8, 2);
            $table->decimal('other_assets', 8, 2);
            $table->decimal('accounts_payable', 8, 2);
            $table->decimal('current_debt', 8, 2);
            $table->decimal('accrued_liabilities', 8, 2);
            $table->decimal('unearned_revenues', 8, 2);
            $table->decimal('other_current_liabilities', 8, 2);
            $table->decimal('long_term_debt', 8, 2);
            $table->decimal('deferred_income_taxes', 8, 2);
            $table->decimal('deferred_rent_expense', 8, 2);
            $table->decimal('other_liabilities', 8, 2);
            $table->decimal('paid_in_capital', 8, 2);
            $table->decimal('retained_earnings', 8, 2);
            $table->decimal('other_equity_accounts', 8, 2);
            $table->foreign('listing_id')->references('id')->on('listings');
        });

        Schema::create('revenues', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('historical_financial_id');
            $table->string('name');
            $table->decimal('amount', 8, 2);
            $table->foreign('historical_financial_id')->references('id')->on('historical_financials');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('historical_financial_id');
            $table->string('name');
            $table->decimal('amount', 8, 2);
            $table->foreign('historical_financial_id')->references('id')->on('historical_financials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historical_financials');
    }
}
