<?php

use Faker\Generator as Faker;

$factory->define(App\ListingCompletionScoreTotal::class, function (Faker $faker) {
    // Historical Financials
    $sources_of_income = $faker->numberBetween(0, 5);
    $employee_related_expenses = $faker->numberBetween(0, 5);
    $office_related_expenses = $faker->numberBetween(0, 5);
    $selling_general_and_administrative_expenses = $faker->numberBetween(0, 5);
    $finance_related_expenses = $faker->numberBetween(0, 5);
    $other_cash_flow_items = $faker->numberBetween(0, 5);
    $non_recurring_personal_or_extra_expenses = $faker->numberBetween(0, 5);
    $balance_sheet_recurring_assets = $faker->numberBetween(0, 5);
    $balance_sheet_long_term_assets = $faker->numberBetween(0, 5);
    $balance_sheet_current_liabilities = $faker->numberBetween(0, 5);
    $balance_sheet_long_term_liabilities = $faker->numberBetween(0, 5);
    $balance_sheet_shareholders_equity = $faker->numberBetween(0, 5);
    $historical_financials = array_sum([
        $sources_of_income,
        $employee_related_expenses,
        $office_related_expenses,
        $selling_general_and_administrative_expenses,
        $finance_related_expenses,
        $other_cash_flow_items,
        $non_recurring_personal_or_extra_expenses,
        $balance_sheet_recurring_assets,
        $balance_sheet_long_term_assets,
        $balance_sheet_current_liabilities,
        $balance_sheet_long_term_liabilities,
        $balance_sheet_shareholders_equity,
    ]);

    // Business Overview
    $basics = $faker->numberBetween(0, 12);
    $more_about_the_business = $faker->numberBetween(0, 9);
    $financial_details = $faker->numberBetween(0, 50);
    $business_details = $faker->numberBetween(0, 20);
    $transaction_considerations = $faker->numberBetween(0, 20);
    $uploads = $faker->numberBetween(0, 13);
    $business_overview = array_sum([
        $basics,
        $more_about_the_business,
        $financial_details,
        $business_details,
        $transaction_considerations,
        $uploads,
    ]);

    return [
        'listing_id' => function () {
            return factory('App\Listing')->create();
        },
        'custom_penalty' => 0,

        // Total
        'total' => $business_overview + $historical_financials,

        // Historical Financials.
        'historical_financials' => $historical_financials,
        'sources_of_income' => $sources_of_income,
        'employee_related_expenses' => $employee_related_expenses,
        'office_related_expenses' => $office_related_expenses,
        'selling_general_and_administrative_expenses' => $selling_general_and_administrative_expenses,
        'finance_related_expenses' => $finance_related_expenses,
        'other_cash_flow_items' => $other_cash_flow_items,
        'non_recurring_personal_or_extra_expenses' => $non_recurring_personal_or_extra_expenses,
        'balance_sheet_recurring_assets' => $balance_sheet_recurring_assets,
        'balance_sheet_long_term_assets' => $balance_sheet_long_term_assets,
        'balance_sheet_current_liabilities' => $balance_sheet_current_liabilities,
        'balance_sheet_long_term_liabilities' => $balance_sheet_long_term_liabilities,
        'balance_sheet_shareholders_equity' => $balance_sheet_shareholders_equity,

        // Business Overview
        'business_overview' => $business_overview,
        'basics' => $basics,
        'more_about_the_business' => $more_about_the_business,
        'financial_details' => $financial_details,
        'business_details' => $business_details,
        'transaction_considerations' => $transaction_considerations,
        'uploads' => $uploads,
    ];
});
