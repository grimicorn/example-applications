<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixHistoricalFinancials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historical_financials', function (Blueprint $table) {
            $table->decimal('employee_wages_benefits', 8, 2)->nullable()->change();
            $table->decimal('employee_education_training', 8, 2)->nullable()->change();
            $table->decimal('contractor_expenses', 8, 2)->nullable()->change();
            $table->decimal('utilities', 8, 2)->nullable()->change();
            $table->decimal('rent_lease_expenses', 8, 2)->nullable()->change();
            $table->decimal('office_supplies', 8, 2)->nullable()->change();
            $table->decimal('cost_goods_sold', 8, 2)->nullable()->change();
            $table->decimal('transportation', 8, 2)->nullable()->change();
            $table->decimal('meals_entertainment', 8, 2)->nullable()->change();
            $table->decimal('travel_expenses', 8, 2)->nullable()->change();
            $table->decimal('professional_services', 8, 2)->nullable()->change();
            $table->decimal('interest_expense', 8, 2)->nullable()->change();
            $table->decimal('depreciation', 8, 2)->nullable()->change();
            $table->decimal('amortization', 8, 2)->nullable()->change();
            $table->decimal('general_operational_expenses', 8, 2)->nullable()->change();
            $table->decimal('business_taxes', 8, 2)->nullable()->change();
            $table->decimal('capital_expenditures', 8, 2)->nullable()->change();
            $table->decimal('stock_based_compensation', 8, 2)->nullable()->change();
            $table->decimal('change_working_capital', 8, 2)->nullable()->change();
            $table->decimal('cash_equivalents', 8, 2)->nullable()->change();
            $table->decimal('investments', 8, 2)->nullable()->change();
            $table->decimal('accounts_receivable', 8, 2)->nullable()->change();
            $table->decimal('inventory', 8, 2)->nullable()->change();
            $table->decimal('prepaid_expenses', 8, 2)->nullable()->change();
            $table->decimal('other_current_assets', 8, 2)->nullable()->change();
            $table->decimal('property_plant_equipment', 8, 2)->nullable()->change();
            $table->decimal('goodwill', 8, 2)->nullable()->change();
            $table->decimal('intangible_assets', 8, 2)->nullable()->change();
            $table->decimal('other_assets', 8, 2)->nullable()->change();
            $table->decimal('accounts_payable', 8, 2)->nullable()->change();
            $table->decimal('current_debt', 8, 2)->nullable()->change();
            $table->decimal('accrued_liabilities', 8, 2)->nullable()->change();
            $table->decimal('unearned_revenues', 8, 2)->nullable()->change();
            $table->decimal('other_current_liabilities', 8, 2)->nullable()->change();
            $table->decimal('long_term_debt', 8, 2)->nullable()->change();
            $table->decimal('deferred_income_taxes', 8, 2)->nullable()->change();
            $table->decimal('deferred_rent_expense', 8, 2)->nullable()->change();
            $table->decimal('other_liabilities', 8, 2)->nullable()->change();
            $table->decimal('paid_in_capital', 8, 2)->nullable()->change();
            $table->decimal('retained_earnings', 8, 2)->nullable()->change();
            $table->decimal('other_equity_accounts', 8, 2)->nullable()->change();
        });

        Schema::table('historical_financials', function (Blueprint $table) {
            if (app()->environment('testing')) {
                return;
            }

            if (Schema::hasColumn('historical_financials', 'number_transactions')) {
                $table->dropColumn('number_transactions');
            }

            if (Schema::hasColumn('historical_financials', 'number_customers')) {
                $table->dropColumn('number_customers');
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
