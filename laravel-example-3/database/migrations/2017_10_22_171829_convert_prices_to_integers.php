<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertPricesToIntegers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }

        // Historical Financials table
        DB::statement('ALTER TABLE historical_financials MODIFY employee_wages_benefits INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY employee_education_training INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY contractor_expenses INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY utilities INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY rent_lease_expenses INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY office_supplies INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY cost_goods_sold INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY transportation INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY meals_entertainment INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY travel_expenses INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY professional_services INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY interest_expense INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY depreciation INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY amortization INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY general_operational_expenses INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY business_taxes INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY capital_expenditures INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY stock_based_compensation INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY change_working_capital INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY cash_equivalents INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY investments INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY accounts_receivable INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY inventory INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY prepaid_expenses INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY other_current_assets INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY property_plant_equipment INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY goodwill INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY intangible_assets INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY other_assets INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY accounts_payable INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY current_debt INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY accrued_liabilities INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY unearned_revenues INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY other_current_liabilities INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY long_term_debt INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY deferred_income_taxes INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY deferred_rent_expense INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY other_liabilities INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY paid_in_capital INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY retained_earnings INT;');
        DB::statement('ALTER TABLE historical_financials MODIFY other_equity_accounts INT;');

        // Listings table
        DB::statement('ALTER TABLE listings MODIFY asking_price INT;');
        DB::statement('ALTER TABLE listings MODIFY revenue INT;');
        DB::statement('ALTER TABLE listings MODIFY discretionary_cash_flow INT;');
        DB::statement('ALTER TABLE listings MODIFY pre_tax_earnings INT;');
        DB::statement('ALTER TABLE listings MODIFY ebitda INT;');
        DB::statement('ALTER TABLE listings MODIFY real_estate_estimated INT;');
        DB::statement('ALTER TABLE listings MODIFY fixtures_equipment_estimated INT;');
        DB::statement('ALTER TABLE listings MODIFY inventory_estimated INT;');

        // Saved Searches table
        DB::statement('ALTER TABLE saved_searches MODIFY asking_price_min INT');
        DB::statement('ALTER TABLE saved_searches MODIFY asking_price_max INT');
        DB::statement('ALTER TABLE saved_searches MODIFY cash_flow_min INT');
        DB::statement('ALTER TABLE saved_searches MODIFY cash_flow_max INT');
        DB::statement('ALTER TABLE saved_searches MODIFY pre_tax_income_min INT');
        DB::statement('ALTER TABLE saved_searches MODIFY pre_tax_income_max INT');
        DB::statement('ALTER TABLE saved_searches MODIFY ebitda_min INT');
        DB::statement('ALTER TABLE saved_searches MODIFY ebitda_max INT');
        DB::statement('ALTER TABLE saved_searches MODIFY revenue_min INT');
        DB::statement('ALTER TABLE saved_searches MODIFY revenue_max INT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->environment('testing')) {
            return;
        }

        Schema::table('historical_financials', function (Blueprint $table) {
            $table->decimal('employee_wages_benefits', 8, 2)->change();
            $table->decimal('employee_education_training', 8, 2)->change();
            $table->decimal('contractor_expenses', 8, 2)->change();
            $table->decimal('utilities', 8, 2)->change();
            $table->decimal('rent_lease_expenses', 8, 2)->change();
            $table->decimal('office_supplies', 8, 2)->change();
            $table->decimal('cost_goods_sold', 8, 2)->change();
            $table->decimal('transportation', 8, 2)->change();
            $table->decimal('meals_entertainment', 8, 2)->change();
            $table->decimal('travel_expenses', 8, 2)->change();
            $table->decimal('professional_services', 8, 2)->change();
            $table->decimal('interest_expense', 8, 2)->change();
            $table->decimal('depreciation', 8, 2)->change();
            $table->decimal('amortization', 8, 2)->change();
            $table->decimal('general_operational_expenses', 8, 2)->change();
            $table->decimal('business_taxes', 8, 2)->change();
            $table->decimal('capital_expenditures', 8, 2)->change();
            $table->decimal('stock_based_compensation', 8, 2)->change();
            $table->decimal('change_working_capital', 8, 2)->change();
            $table->decimal('cash_equivalents', 8, 2)->change();
            $table->decimal('investments', 8, 2)->change();
            $table->decimal('accounts_receivable', 8, 2)->change();
            $table->decimal('inventory', 8, 2)->change();
            $table->decimal('prepaid_expenses', 8, 2)->change();
            $table->decimal('other_current_assets', 8, 2)->change();
            $table->decimal('property_plant_equipment', 8, 2)->change();
            $table->decimal('goodwill', 8, 2)->change();
            $table->decimal('intangible_assets', 8, 2)->change();
            $table->decimal('other_assets', 8, 2)->change();
            $table->decimal('accounts_payable', 8, 2)->change();
            $table->decimal('current_debt', 8, 2)->change();
            $table->decimal('accrued_liabilities', 8, 2)->change();
            $table->decimal('unearned_revenues', 8, 2)->change();
            $table->decimal('other_current_liabilities', 8, 2)->change();
            $table->decimal('long_term_debt', 8, 2)->change();
            $table->decimal('deferred_income_taxes', 8, 2)->change();
            $table->decimal('deferred_rent_expense', 8, 2)->change();
            $table->decimal('other_liabilities', 8, 2)->change();
            $table->decimal('paid_in_capital', 8, 2)->change();
            $table->decimal('retained_earnings', 8, 2)->change();
            $table->decimal('other_equity_accounts', 8, 2)->change();
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->double('asking_price', 15, 2)->nullable()->change();
            $table->double('revenue', 15, 2)->nullable()->change();
            $table->double('discretionary_cash_flow', 15, 2)->nullable()->change();
            $table->double('pre_tax_earnings', 15, 2)->nullable()->change();
            $table->double('ebitda', 15, 2)->nullable()->change();
            $table->double('real_estate_estimated', 15, 2)->nullable()->change();
            $table->double('fixtures_equipment_estimated', 15, 2)->nullable()->change();
            $table->double('inventory_estimated', 15, 2)->nullable()->change();
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            $table->double('asking_price_min', 15, 2)->nullable()->change();
            $table->double('asking_price_max', 15, 2)->nullable()->change();
            $table->double('cash_flow_min', 15, 2)->nullable()->change();
            $table->double('cash_flow_max', 15, 2)->nullable()->change();
            $table->double('pre_tax_income_min', 15, 2)->nullable()->change();
            $table->double('pre_tax_income_max', 15, 2)->nullable()->change();
            $table->double('ebitda_min', 15, 2)->nullable()->change();
            $table->double('ebitda_max', 15, 2)->nullable()->change();
            $table->double('revenue_min', 15, 2)->nullable()->change();
            $table->double('revenue_max', 15, 2)->nullable()->change();
        });
    }
}
