<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertPricesToBigInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('listings', function (Blueprint $table) {
            $table->bigInteger('asking_price')->nullable()->change();
            $table->bigInteger('revenue')->nullable()->change();
            $table->bigInteger('ebitda')->nullable()->change();
            $table->bigInteger('pre_tax_earnings')->nullable()->change();
            $table->bigInteger('discretionary_cash_flow')->nullable()->change();
            $table->bigInteger('real_estate_estimated')->nullable()->change();
            $table->bigInteger('fixtures_equipment_estimated')->nullable()->change();
            $table->bigInteger('inventory_estimated')->nullable()->change();
        });

        Schema::table('listing_exit_surveys', function (Blueprint $table) {
            $table->bigInteger('final_sale_price')->nullable()->change();
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            $table->bigInteger('asking_price_min')->nullable()->change();
            $table->bigInteger('asking_price_max')->nullable()->change();
            $table->bigInteger('cash_flow_min')->nullable()->change();
            $table->bigInteger('cash_flow_max')->nullable()->change();
            $table->bigInteger('pre_tax_income_min')->nullable()->change();
            $table->bigInteger('pre_tax_income_max')->nullable()->change();
            $table->bigInteger('ebitda_min')->nullable()->change();
            $table->bigInteger('ebitda_max')->nullable()->change();
            $table->bigInteger('revenue_min')->nullable()->change();
            $table->bigInteger('revenue_max')->nullable()->change();
        });

        Schema::table('user_desired_purchase_criterias', function (Blueprint $table) {
            $table->bigInteger('asking_price_minimum')->nullable()->change();
            $table->bigInteger('asking_price_maximum')->nullable()->change();
            $table->bigInteger('revenue_minimum')->nullable()->change();
            $table->bigInteger('revenue_maximum')->nullable()->change();
            $table->bigInteger('ebitda_minimum')->nullable()->change();
            $table->bigInteger('ebitda_maximum')->nullable()->change();
            $table->bigInteger('pre_tax_income_minimum')->nullable()->change();
            $table->bigInteger('pre_tax_income_maximum')->nullable()->change();
            $table->bigInteger('discretionary_cash_flow_minimum')->nullable()->change();
            $table->bigInteger('discretionary_cash_flow_maximum')->nullable()->change();
        });

        Schema::table('revenue_lines', function (Blueprint $table) {
            $table->bigInteger('amount')->nullable()->change();
        });

        Schema::table('expense_lines', function (Blueprint $table) {
            $table->bigInteger('amount')->nullable()->change();
        });

        Schema::table('historical_financials', function (Blueprint $table) {
            $table->bigInteger('employee_wages_benefits')->nullable()->change();
            $table->bigInteger('employee_education_training')->nullable()->change();
            $table->bigInteger('contractor_expenses')->nullable()->change();
            $table->bigInteger('utilities')->nullable()->change();
            $table->bigInteger('rent_lease_expenses')->nullable()->change();
            $table->bigInteger('office_supplies')->nullable()->change();
            $table->bigInteger('cost_goods_sold')->nullable()->change();
            $table->bigInteger('transportation')->nullable()->change();
            $table->bigInteger('meals_entertainment')->nullable()->change();
            $table->bigInteger('travel_expenses')->nullable()->change();
            $table->bigInteger('professional_services')->nullable()->change();
            $table->bigInteger('interest_expense')->nullable()->change();
            $table->bigInteger('depreciation')->nullable()->change();
            $table->bigInteger('amortization')->nullable()->change();
            $table->bigInteger('general_operational_expenses')->nullable()->change();
            $table->bigInteger('business_taxes')->nullable()->change();
            $table->bigInteger('capital_expenditures')->nullable()->change();
            $table->bigInteger('stock_based_compensation')->nullable()->change();
            $table->bigInteger('change_working_capital')->nullable()->change();
            $table->bigInteger('cash_equivalents')->nullable()->change();
            $table->bigInteger('investments')->nullable()->change();
            $table->bigInteger('accounts_receivable')->nullable()->change();
            $table->bigInteger('inventory')->nullable()->change();
            $table->bigInteger('prepaid_expenses')->nullable()->change();
            $table->bigInteger('other_current_assets')->nullable()->change();
            $table->bigInteger('property_plant_equipment')->nullable()->change();
            $table->bigInteger('goodwill')->nullable()->change();
            $table->bigInteger('intangible_assets')->nullable()->change();
            $table->bigInteger('other_assets')->nullable()->change();
            $table->bigInteger('accounts_payable')->nullable()->change();
            $table->bigInteger('current_debt')->nullable()->change();
            $table->bigInteger('accrued_liabilities')->nullable()->change();
            $table->bigInteger('unearned_revenues')->nullable()->change();
            $table->bigInteger('other_current_liabilities')->nullable()->change();
            $table->bigInteger('long_term_debt')->nullable()->change();
            $table->bigInteger('deferred_income_taxes')->nullable()->change();
            $table->bigInteger('deferred_rent_expense')->nullable()->change();
            $table->bigInteger('other_liabilities')->nullable()->change();
            $table->bigInteger('paid_in_capital')->nullable()->change();
            $table->bigInteger('retained_earnings')->nullable()->change();
            $table->bigInteger('other_equity_accounts')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('listings', function (Blueprint $table) {
            $table->integer('asking_price')->nullable()->change();
            $table->integer('revenue')->nullable()->change();
            $table->integer('ebitda')->nullable()->change();
            $table->integer('pre_tax_earnings')->nullable()->change();
            $table->integer('discretionary_cash_flow')->nullable()->change();
            $table->integer('real_estate_estimated')->nullable()->change();
            $table->integer('fixtures_equipment_estimated')->nullable()->change();
            $table->integer('inventory_estimated')->nullable()->change();
        });

        Schema::table('listing_exit_surveys', function (Blueprint $table) {
            $table->integer('final_sale_price')->nullable()->change();
        });

        Schema::table('saved_searches', function (Blueprint $table) {
            $table->integer('zip_code_radius')->nullable()->change();
            $table->integer('asking_price_min')->nullable()->change();
            $table->integer('asking_price_max')->nullable()->change();
            $table->integer('cash_flow_min')->nullable()->change();
            $table->integer('cash_flow_max')->nullable()->change();
            $table->integer('pre_tax_income_min')->nullable()->change();
            $table->integer('pre_tax_income_max')->nullable()->change();
            $table->integer('ebitda_min')->nullable()->change();
            $table->integer('ebitda_max')->nullable()->change();
            $table->integer('revenue_min')->nullable()->change();
            $table->integer('revenue_max')->nullable()->change();
        });

        Schema::table('user_desired_purchase_criterias', function (Blueprint $table) {
            $table->string('asking_price_minimum')->nullable()->change();
            $table->string('asking_price_maximum')->nullable()->change();
            $table->string('revenue_minimum')->nullable()->change();
            $table->string('revenue_maximum')->nullable()->change();
            $table->string('ebitda_minimum')->nullable()->change();
            $table->string('ebitda_maximum')->nullable()->change();
            $table->string('pre_tax_income_minimum')->nullable()->change();
            $table->string('pre_tax_income_maximum')->nullable()->change();
            $table->string('discretionary_cash_flow_minimum')->nullable()->change();
            $table->string('discretionary_cash_flow_maximum')->nullable()->change();
        });

        Schema::table('revenue_lines', function (Blueprint $table) {
            $table->integer('amount')->nullable()->change();
        });

        Schema::table('expense_lines', function (Blueprint $table) {
            $table->integer('amount')->nullable()->change();
        });

        Schema::table('historical_financials', function (Blueprint $table) {
            $table->integer('employee_wages_benefits')->nullable()->change();
            $table->integer('employee_education_training')->nullable()->change();
            $table->integer('contractor_expenses')->nullable()->change();
            $table->integer('utilities')->nullable()->change();
            $table->integer('rent_lease_expenses')->nullable()->change();
            $table->integer('office_supplies')->nullable()->change();
            $table->integer('cost_goods_sold')->nullable()->change();
            $table->integer('transportation')->nullable()->change();
            $table->integer('meals_entertainment')->nullable()->change();
            $table->integer('travel_expenses')->nullable()->change();
            $table->integer('professional_services')->nullable()->change();
            $table->integer('interest_expense')->nullable()->change();
            $table->integer('depreciation')->nullable()->change();
            $table->integer('amortization')->nullable()->change();
            $table->integer('general_operational_expenses')->nullable()->change();
            $table->integer('business_taxes')->nullable()->change();
            $table->integer('capital_expenditures')->nullable()->change();
            $table->integer('stock_based_compensation')->nullable()->change();
            $table->integer('change_working_capital')->nullable()->change();
            $table->integer('cash_equivalents')->nullable()->change();
            $table->integer('investments')->nullable()->change();
            $table->integer('accounts_receivable')->nullable()->change();
            $table->integer('inventory')->nullable()->change();
            $table->integer('prepaid_expenses')->nullable()->change();
            $table->integer('other_current_assets')->nullable()->change();
            $table->integer('property_plant_equipment')->nullable()->change();
            $table->integer('goodwill')->nullable()->change();
            $table->integer('intangible_assets')->nullable()->change();
            $table->integer('other_assets')->nullable()->change();
            $table->integer('accounts_payable')->nullable()->change();
            $table->integer('current_debt')->nullable()->change();
            $table->integer('accrued_liabilities')->nullable()->change();
            $table->integer('unearned_revenues')->nullable()->change();
            $table->integer('other_current_liabilities')->nullable()->change();
            $table->integer('long_term_debt')->nullable()->change();
            $table->integer('deferred_income_taxes')->nullable()->change();
            $table->integer('deferred_rent_expense')->nullable()->change();
            $table->integer('other_liabilities')->nullable()->change();
            $table->integer('paid_in_capital')->nullable()->change();
            $table->integer('retained_earnings')->nullable()->change();
            $table->integer('other_equity_accounts')->nullable()->change();
        });
    }
}
