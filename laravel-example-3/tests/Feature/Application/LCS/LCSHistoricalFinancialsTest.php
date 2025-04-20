<?php

namespace Tests\Feature\Application\LCS;

use App\Listing;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\ListingCompletionScore\ListingCompletionScore;
use Illuminate\Support\Carbon;

/**
 * @group lcs
 * @codingStandardsIgnoreFile
 */
class LCSHistoricalFinancialsTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_calculates_listing_hsf_sources_of_income_lcs()
    {
        // sources_of_income | 5 (inputs: 4 - repeater)
        $listing = $this->createEmptyListingHistoricalFinancials();
        $inputs = 4;
        $points = 5;
        $section = 'sources_of_income';
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;

        // Validate that the listing is empty.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // Since this is such an odd calculation to handle with an unknown set of fields
        // Lets first start by checking if the total/percentage stays the same no matter
        // how many rows are added.

        // 1 Row is $inputs / 5
        $revenue = $this->createFullAllYearRevenueLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // 2 Rows is ($inputs * 2) / 5
        $revenue = $this->createFullAllYearRevenueLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // 3 Rows is ($inputs * 3) / 5
        $revenue = $this->createFullAllYearRevenueLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // 4 Rows is ($inputs * 4) / 5
        $revenue = $this->createFullAllYearRevenueLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // Now lets check if the value increments correctly for each input
        $listing = $this->createEmptyListingHistoricalFinancials();
        $revenue = $this->createEmptyAllYearRevenueLines($listing);

        // For no inputs
        $this->assertRepeaterSection($listing, $points, $section, 0);

        // For the name the value should be ($inputs/$points)
        $revenue->name = 'Name';
        $revenue->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 0);

        // For 1 amount the total should be (entered / inputs) × points
        $revenue->lines->get(0)->amount = 2000;
        $revenue->lines->get(0)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 1.25);

        // For 2 amounts the total should be (entered / inputs) × points
        $revenue->lines->get(1)->amount = 2000;
        $revenue->lines->get(1)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 2.5);

        // For 3 amounts the total should be (entered / inputs) × points
        $revenue->lines->get(2)->amount = 2000;
        $revenue->lines->get(2)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 3.75);

        // For 4 amounts the total should be (entered / inputs) × points
        $revenue->lines->get(3)->amount = 2000;
        $revenue->lines->get(3)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 5);

        // Now lets add another line of revenue and make sure keeps going the same
        $revenue = $this->createEmptyAllYearRevenueLines($listing);

        // For 2 rows second row with no inputs
        $this->assertRepeaterSection($listing, $points, $section, 2.5);

        // For 2 rows second row with name the value should be the same as no inputs
        $revenue->name = 'Name';
        $revenue->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 2.5);

        // For 2 rows second row with name and 1 amount the total should be (entered / (inputs × 2)) × points
        $revenue->lines->get(0)->amount = 2000;
        $revenue->lines->get(0)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 3.125);

        // For 2 rows second row with name and 2 amounts the total should be (entered / (inputs × 2)) × points
        $revenue->lines->get(1)->amount = 2000;
        $revenue->lines->get(1)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 3.75);

        // For 2 rows second row with name and 3 amounts the total should be (entered / (inputs × 2)) × points
        $revenue->lines->get(2)->amount = 2000;
        $revenue->lines->get(2)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 4.375);

        // For 2 rows second row with name and 4 amounts the total should be (entered / (inputs × 2)) × points
        $revenue->lines->get(3)->amount = 2000;
        $revenue->lines->get(3)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 5);
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_employee_related_expenses_lcs()
    {
        // employee_related_expenses | points:5 | inputs:12
        $section = 'employee_related_expenses';
        $inputs = 12;
        $points = 5;
        $attributes = [];

        // Employee Wages Benefits
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'employee_wages_benefits',
            $inputs,
            $points
        );

        // Employee Education Training
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'employee_education_training',
            $inputs,
            $points
        );

        // Contractor Expenses
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'contractor_expenses',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_office_related_expenses_lcs()
    {
        // office_related_expenses | 5 (inputs:12)
        $section = 'office_related_expenses';
        $inputs = 12;
        $points = 5;
        $attributes = [];

        // Utilities
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'utilities',
            $inputs,
            $points
        );

        // Rent Lease Expenses
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'rent_lease_expenses',
            $inputs,
            $points
        );

        // Office Supplies
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'office_supplies',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_sga_expenses_lcs()
    {
        // sga_expenses | 5 (inputs:20)
        $section = 'selling_general_and_administrative_expenses';
        $inputs = 20;
        $points = 5;
        $attributes = [];

        // Cost Goods Sold
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'cost_goods_sold',
            $inputs,
            $points
        );

        // Transportation
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'transportation',
            $inputs,
            $points
        );

        // Meals Entertainment
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'meals_entertainment',
            $inputs,
            $points
        );

        // Travel Expenses
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'travel_expenses',
            $inputs,
            $points
        );

        // Professional Services
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'professional_services',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_finance_related_lcs()
    {
        // finance_related | 5 (inputs:20)
        $section = 'finance_related_expenses';
        $inputs = 20;
        $points = 5;
        $attributes = [];

        // Interest Expense
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'interest_expense',
            $inputs,
            $points
        );

        // Depreciation
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'depreciation',
            $inputs,
            $points
        );

        // Amortization
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'amortization',
            $inputs,
            $points
        );

        // General Operational Expenses
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'general_operational_expenses',
            $inputs,
            $points
        );

        // Business Taxes
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'business_taxes',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_other_cash_flow_items_lcs()
    {
        // other_cash_flow_items | 5 (inputs:12)
        $section = 'other_cash_flow_items';
        $inputs = 12;
        $points = 5;
        $attributes = [];

        // Capital Expenditures
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'capital_expenditures',
            $inputs,
            $points
        );

        // Stock Based Compensation
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'stock_based_compensation',
            $inputs,
            $points
        );

        // Change Working Capital
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'change_working_capital',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_non_recurring_expenses_lcs()
    {
        $listing = $this->createEmptyListingHistoricalFinancials();
        $inputs = 4;
        $points = 5;
        $section = 'non_recurring_personal_or_extra_expenses';

        // Validate that the listing is empty.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // Since this is such an odd calculation to handle with an unknown set of fields
        // Lets first start by checking if the total/percentage stays the same no matter
        // how many rows are added.

        // 1 Row is $inputs / 5
        $expense = $this->createFullAllYearExpenseLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // 2 Rows is ($inputs * 2) / 5
        $expense = $this->createFullAllYearExpenseLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // 3 Rows is ($inputs * 3) / 5
        $expense = $this->createFullAllYearExpenseLines($listing);
        $this->assertRepeaterSection($listing, $points, $section);

        // 4 Rows is ($inputs * 4) / 5
        $expense = $this->createFullAllYearExpenseLines($listing);
        $this->assertRepeaterSection($listing, $points, $section, 5);

        // Now lets check if the value increments correctly for each input
        $listing = $this->createEmptyListingHistoricalFinancials();
        $expense = $this->createEmptyAllYearExpenseLines($listing);

        // For no inputs
        $this->assertRepeaterSection($listing, $points, $section, 0);

        // For the name the value should be 0
        $expense->name = 'Name';
        $expense->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 0);

        // For 1 amount the total should be (entered / inputs) × points
        $expense->lines->get(0)->amount = 2000;
        $expense->lines->get(0)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 1.25);

        // For 2 amounts the total should be (entered / inputs) × points
        $expense->lines->get(1)->amount = 2000;
        $expense->lines->get(1)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 2.5);

        // For 3 amounts the total should be (entered / inputs) × points
        $expense->lines->get(2)->amount = 2000;
        $expense->lines->get(2)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 3.75);

        // For 4 amounts the total should be (entered / inputs) × points
        $expense->lines->get(3)->amount = 2000;
        $expense->lines->get(3)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 5);

        // Now lets add another line of expense and make sure keeps going the same
        $expense = $this->createEmptyAllYearExpenseLines($listing);

        // For 2 rows second row with no inputs
        $this->assertRepeaterSection($listing, $points, $section, 2.5);

        // For 2 rows second row with name the value should be the same as no inputs
        $expense->name = 'Name';
        $expense->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 2.5);

        // For 2 rows second row with name and 1 amount the total should be (entered / (inputs × 2)) × points
        $expense->lines->get(0)->amount = 2000;
        $expense->lines->get(0)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 3.125);

        // For 2 rows second row with name and 2 amounts the total should be (entered / (inputs × 2)) × points
        $expense->lines->get(1)->amount = 2000;
        $expense->lines->get(1)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 3.75);

        // For 2 rows second row with name and 3 amounts the total should be (entered / (inputs × 2)) × points
        $expense->lines->get(2)->amount = 2000;
        $expense->lines->get(2)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 4.375);

        // For 2 rows second row with name and 4 amounts the total should be (entered / (inputs × 2)) × points
        $expense->lines->get(3)->amount = 2000;
        $expense->lines->get(3)->save();
        $listing = $listing->fresh();
        $this->assertRepeaterSection($listing, $points, $section, 5);
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_current_assets_lcs()
    {
        // current_assets | 5 (inputs:24)
        $section = 'balance_sheet_recurring_assets';
        $inputs = 24;
        $points = 5;
        $attributes = [];

        // Cash Equivalents
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'cash_equivalents',
            $inputs,
            $points
        );

        // Investments
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'investments',
            $inputs,
            $points
        );

        // Accounts Receivable
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'accounts_receivable',
            $inputs,
            $points
        );

        // Inventory
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'inventory',
            $inputs,
            $points
        );

        // Prepaid Expenses
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'prepaid_expenses',
            $inputs,
            $points
        );

        // Other Current Assets
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'other_current_assets',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_other_assets_lcs()
    {
        // other_assets | 5 (inputs:16)
        $section = 'balance_sheet_long_term_assets';
        $inputs = 16;
        $points = 5;
        $attributes = [];

        // Property Plant Equipment
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'property_plant_equipment',
            $inputs,
            $points
        );

        // Goodwill
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'goodwill',
            $inputs,
            $points
        );

        // Intangible Assets
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'intangible_assets',
            $inputs,
            $points
        );

        // Other Assets
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'other_assets',
            $inputs,
            $points
        );


        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_current_liabilities_lcs()
    {
        // current_liabilities | 5 (inputs:20)
        $section = 'balance_sheet_current_liabilities';
        $inputs = 20;
        $points = 5;
        $attributes = [];

        // Accounts Payable
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'accounts_payable',
            $inputs,
            $points
        );

        // Current Debt
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'current_debt',
            $inputs,
            $points
        );

        // Accrued Liabilities
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'accrued_liabilities',
            $inputs,
            $points
        );

        // Unearned Revenues
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'unearned_revenues',
            $inputs,
            $points
        );

        // Other Current Liabilities
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'other_current_liabilities',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_other_liabilities_lcs()
    {
        // other_liabilities | 5 (inputs:16)
        $section = 'balance_sheet_long_term_liabilities';
        $inputs = 16;
        $points = 5;
        $attributes = [];

        // Long Term Debt
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'long_term_debt',
            $inputs,
            $points
        );

        // Deferred Income Taxes
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'deferred_income_taxes',
            $inputs,
            $points
        );

        // Deferred Rent Expense
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'deferred_rent_expense',
            $inputs,
            $points
        );

        // Other Liabilities
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'other_liabilities',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_calculates_listing_hsf_shareholders_equity_lcs()
    {
        // shareholders_equity | 5 (inputs:12)
        $section = 'balance_sheet_shareholders_equity';
        $inputs = 12;
        $points = 5;
        $attributes = [];

        // Paid In Capital
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'paid_in_capital',
            $inputs,
            $points
        );

        // Retained Earnings
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'retained_earnings',
            $inputs,
            $points
        );

        // Other Equity Accounts
        $this->assertAllYearSectionCompletionScorePart(
            $section,
            $attributes[] = 'other_equity_accounts',
            $inputs,
            $points
        );

        // Check The Total
        $this->assertSectionCompletionScoreTotal(
            $section,
            $attributes,
            $inputs,
            $points
        );
    }

    /**
    * @test
    */
    public function it_ignores_years_before_a_listing_year_establsished()
    {
        $listing = $this->createEmptyListingHistoricalFinancials();

        // To continue to make sure it is empty we need to set
        // the establish date farther in the past
        $listing->year_established = Carbon::now()->subYears(10)->format('Y');
        $listing->save();
        $listing = $listing->fresh();

        // To start out with the completion score is 0
        $lcs = new ListingCompletionScore($listing);
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals(0, $financial->totalPercentage());

        // Now lets set the historical financial most recent data
        // to the current year and the 1st quarter
        $listing->hf_most_recent_year = Carbon::now()->subYear();
        $listing->hf_most_recent_quarter = 1;
        $listing->year_established = $listing->hf_most_recent_year->subYear()->format('Y'); // This will "disable" year 1
        $listing->save();
        $listing = $listing->fresh();

        // Now we need to fill up the hisotrical financials
        $listing->historicalFinancials->each(function ($financial) use ($listing) {
            $year = intval($financial->year->format('Y'));

            if ($year < intval($listing->year_established)) {
                return;
            }

            $fill = collect(factory('App\HistoricalFinancial')->make()->toArray())
            ->except([
                'year',
                'listing_id'
            ])->toArray();
            $financial->forceFill($fill);
            $financial->save();
        });

        // The main historical financials section are each 5 points and
        // there are 10 sections. So with only the 3 years out of the 4 full
        //  they should still receive 100% credit but with a lower overall
        // possible point total due to Year 1 not being factored in
        $pointRemainingPercentage = .75; // 75% because 3/4 fields will still need to be filled in
        $totalPoints = (60 * $pointRemainingPercentage);
        $generalPoints = (50 * $pointRemainingPercentage);
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals($generalPoints, $financial->total());
        $this->assertEquals(($generalPoints/$totalPoints), $financial->totalPercentage());

        // Next up is the revenue section we are going to fill out the most recent 2
        // years so we can keep it the same as the previous section.
        // Along with adding the name to the revenue.
        $revenue = $this->createEmptyAllYearRevenueLines($listing);
        $listing->revenues->each(function ($revenue) use ($listing) {
            $revenue->name = 'Name';
            $revenue->save();

            $revenue->lines->each(function ($line) use ($listing, $revenue) {
                $most_recent = intval($listing->hf_most_recent_year->format('Y'));
                $year = intval($line->year->format('Y'));
                if ($year < intval($listing->year_established)) {
                    return;
                }

                $line->amount = 2000;
                $line->save();
            });
        });

        // The revenue section accounts for 5 points so with the previous 5 points
        // when fully filled out the total should be 55
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $revenueGeneralPoints = (55 * $pointRemainingPercentage);
        $this->assertEquals($revenueGeneralPoints, $financial->total());
        $this->assertEquals(($revenueGeneralPoints/$totalPoints), $financial->totalPercentage());

        // Finally is the expense section we are going to fill out the most recent 2
        // years so we can keep it the same as the previous section.
        // Along with adding the name to the expense.
        $expense = $this->createEmptyAllYearExpenseLines($listing);
        $listing->expenses->each(function ($expense) use ($listing) {
            $expense->name = 'Name';
            $expense->save();

            $expense->lines->each(function ($line) use ($listing, $expense) {
                $most_recent = intval($listing->hf_most_recent_year->format('Y'));
                $year = intval($line->year->format('Y'));
                if ($year < intval($listing->year_established)) {
                    return;
                }

                $line->amount = 2000;
                $line->save();
            });
        });

        // The expense section accounts for 5 points so with the previous 5 points
        // when fully filled out the total should be 55
        $lcs = new ListingCompletionScore($listing->fresh());
        $revenueExpenseGeneralPoints = (60 * $pointRemainingPercentage);
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals($revenueExpenseGeneralPoints, $financial->total());
        $this->assertEquals(($revenueExpenseGeneralPoints/$totalPoints), $financial->totalPercentage());
    }

    /**
    * @test
    */
    public function it_ignores_year_4_it_none_available_is_selected()
    {
        $listing = $this->createEmptyListingHistoricalFinancials();

        // To continue to make sure it is empty we need to set
        // the establish date farther in the past
        $listing->year_established = Carbon::now()->subYears(10)->format('Y');
        $listing->hf_most_recent_year = null;
        $listing->hf_most_recent_quarter = null;
        $listing->save();
        $listing = $listing->fresh();

        // To start out with the completion score is 0
        $lcs = new ListingCompletionScore($listing);
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals(0, $financial->totalPercentage());

        // Now set the most recent year to the current year and the quarter to none (0).
        $listing->hf_most_recent_year = Carbon::now()->subYear();
        $listing->hf_most_recent_quarter = 0;
        $listing->save();
        $listing = $listing->fresh();

        // Now we need to fill up the hisotrical financials
        $listing->historicalFinancials->each(function ($financial) use ($listing) {
            $most_recent = intval($listing->hf_most_recent_year->format('Y'));
            $year = intval($financial->year->format('Y'));

            if ($year > $most_recent) {
                return;
            }

            $fill = collect(factory('App\HistoricalFinancial')->make()->toArray())
            ->except([
                'year',
                'listing_id'
            ])->toArray();
            $financial->forceFill($fill);
            $financial->save();
        });

        // The main historical financials section are each 5 points and
        // there are 10 sections. So with only the 3 years out of the 4 full
        //  they should still receive 100% credit
        $pointRemainingPercentage = .75; // 75% because 3/4 fields will still need to be filled in
        $totalPoints = (60 * $pointRemainingPercentage);
        $generalPoints = (50 * $pointRemainingPercentage);
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals($generalPoints, $financial->total());
        $this->assertEquals(($generalPoints / $totalPoints), $financial->totalPercentage());
return;
        // Next up is the revenue section we are going to fill out the most recent 2
        // years so we can keep it the same as the previous section.
        // Along with adding the name to the revenue.
        $revenue = $this->createEmptyAllYearRevenueLines($listing);
        $listing->revenues->each(function ($revenue) use ($listing) {
            $revenue->name = 'Name';
            $revenue->save();

            $revenue->lines->each(function ($line) use ($listing, $revenue) {
                $most_recent = intval($listing->hf_most_recent_year->format('Y'));
                $year = intval($line->year->format('Y'));
                if ($year > $most_recent) {
                    return;
                }

                $line->amount = 2000;
                $line->save();
            });
        });

        // The revenue section accounts for 5 points so with the previous 5 points
        // when fully filled out the total should be 55
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals(55, $financial->total());
        $this->assertEquals((55/60), $financial->totalPercentage());

        // Finally is the expense section we are going to fill out the most recent 2
        // years so we can keep it the same as the previous section.
        // Along with adding the name to the expense.
        $expense = $this->createEmptyAllYearExpenseLines($listing);
        $listing->expenses->each(function ($expense) use ($listing) {
            $expense->name = 'Name';
            $expense->save();

            $expense->lines->each(function ($line) use ($listing, $expense) {
                $most_recent = intval($listing->hf_most_recent_year->format('Y'));
                $year = intval($line->year->format('Y'));
                if ($year > $most_recent) {
                    return;
                }

                $line->amount = 2000;
                $line->save();
            });
        });

        // The expense section accounts for 5 points so with the previous 5 points
        // when fully filled out the total should be 55
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals(60, $financial->total());
        $this->assertEquals((60/60), $financial->totalPercentage());
    }

    /**
     * Validates the listing is empty and has a completion score of 0
     *
     * @param App\Listing $listing
     * @param string $section
     * @return void
     */
    protected function assertEmptyListingCompletionScore(Listing $listing, string $section)
    {
        $lcs = new ListingCompletionScore($listing);
        $financial = $lcs->historicalFinancialCalculations;

        // An empty listing should have a completion score percentage of 0
        // An empty listing should have a section completion score percentage of 0
        $this->assertEquals(0, $financial->total());
        $this->assertEquals(0, $financial->sectionPercentageForDisplay($section));
    }

    /**
     * Validates listing completion score section.
     *
     * @param App\Listing $listing
     * @param string $section
     * @param float $score
     * @param float $percentage
     * @return void
     */
    protected function assertSectionCompletionScore(Listing $listing, string $section, float $score, float $percentage)
    {
        $lcs = (new ListingCompletionScore($listing))->historicalFinancialCalculations;
        $this->assertEquals($percentage, $lcs->sectionPercentageForDisplay($section));
        $this->assertEquals($score, $lcs->sectionTotal($section));
    }

    protected function assertAllYearSectionCompletionScorePart($section, $attribute, $inputs, $points)
    {
        $listing = $this->createEmptyListingHistoricalFinancials();
        $percentage = (1/$inputs);
        $attributeValue = 2000;
        $value = $percentage * $points;

        // Checks if the listing completion score
        $this->assertEmptyListingCompletionScore($listing, $section);

        // Year 1
        $listing = $this->updateForYear($listing, 1, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value, round($percentage * 100));
        $listing = $this->attributesReset($listing, $attribute);

        // Year 2
        $listing = $this->updateForYear($listing, 2, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value, round($percentage * 100));
        $listing = $this->attributesReset($listing, $attribute);

        // Year 3
        $listing = $this->updateForYear($listing, 3, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value, round($percentage * 100));
        $listing = $this->attributesReset($listing, $attribute);

        // Year 4
        $listing = $this->updateForYear($listing, 4, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value, round($percentage * 100));
        $listing = $this->attributesReset($listing, $attribute);

        // All
        $listing = $this->updateForYear($listing, 1, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value, round($percentage * 100));
        $listing = $this->updateForYear($listing, 2, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value * 2, round(($percentage * 2) * 100));
        $listing = $this->updateForYear($listing, 3, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value * 3, round(($percentage * 3) * 100));
        $listing = $this->updateForYear($listing, 4, $attribute, $attributeValue);
        $this->assertSectionCompletionScore($listing, $section, $value * 4, round(($percentage * 4) * 100));
    }

    /**
     * Asserts the completion score total.
     *
     * @param string $section
     * @param array $attributes
     * @param int $inputs
     * @param int $points
     * @return void
     */
    protected function assertSectionCompletionScoreTotal($section, $attributes, $inputs, $points)
    {
        $listing = $this->createEmptyListingHistoricalFinancials();
        $percentage = (1/$inputs);
        $attributeValue = 2000;
        $value = $percentage * $points;

        // Add values to all attributes
        collect($attributes)->each(function ($attribute) use ($listing, $attributeValue) {
            $this->updateForYear($listing, 1, $attribute, $attributeValue);
            $this->updateForYear($listing, 2, $attribute, $attributeValue);
            $this->updateForYear($listing, 3, $attribute, $attributeValue);
            $this->updateForYear($listing, 4, $attribute, $attributeValue);
        });

        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;

        $this->assertEquals(floatval($points/60), $financial->totalPercentage());
        $this->assertEquals(100, $financial->sectionPercentageForDisplay($section));
    }

    /**
     * Creates an empty listing with the previous 4 years of financials
     *
     * @return void
     */
    protected function createEmptyListingHistoricalFinancials()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->create([
            'hf_most_recent_year' => Carbon::now()->subYear(),
            'hf_most_recent_quarter' => Carbon::now()->quarter, // Solves issues with none available disabled inputs
            'year_established' => Carbon::now()->subYears(20), // Solves issues with year established disabled inputs
        ]);

        $hsfYear1 = factory('App\HistoricalFinancial')->states('lcs-empty')->create([
            'year' => $listing->hf_most_recent_year->subYears(2),
            'listing_id' => $listing->id,
        ]);
        $hsfYear2 = factory('App\HistoricalFinancial')->states('lcs-empty')->create([
            'year' => $listing->hf_most_recent_year->subYears(1),
            'listing_id' => $listing->id,
        ]);
        $hsfYear3 = factory('App\HistoricalFinancial')->states('lcs-empty')->create([
            'year' => $listing->hf_most_recent_year,
            'listing_id' => $listing->id,
        ]);
        $hsfYear4 = factory('App\HistoricalFinancial')->states('lcs-empty')->create([
            'year' => $listing->hf_most_recent_year->addYear(),
            'listing_id' => $listing->id,
        ]);

        return $listing->fresh();
    }

    /**
     * Updates historical financial attribute for a given year.
     *
     * @param App\Listing $listing
     * @param int $year
     * @param string $attribute
     * @param mixed $value
     * @return App\Listing
     */
    protected function updateForYear(Listing $listing, int $year, string $attribute, $value)
    {
        $hf = $listing->fresh()->getFinancialsForYear($year);
        $hf->$attribute = $value;
        $hf->save();

        return $listing->fresh();
    }

    /**
     * Resets all values for attribute to null
     *
     * @param App\Listing $listing
     * @param string $attribute
     * @return void
     */
    protected function attributesReset($listing, $attribute)
    {
        $listing->historicalFinancials->each(function ($financial) use ($attribute) {
            $financial->$attribute = null;
            $financial->save();
        });

        return $listing->fresh();
    }

    /**
     * Creates all year revenue lines
     *
     * @return void
     */
    protected function createEmptyRevenueLinesListing()
    {
        $listing = $this->createEmptyListingHistoricalFinancials();


        return $listing->fresh();
    }

    protected function createFullAllYearRevenueLines($listing)
    {
        $revenue = factory('App\Revenue')->states('lcs-full')->create(['listing_id' => $listing->id]);

        factory('App\RevenueLine')->states('lcs-full')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now(),
        ]);

        factory('App\RevenueLine')->states('lcs-full')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now()->subYears(1),
        ]);

        factory('App\RevenueLine')->states('lcs-full')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now()->subYears(2),
        ]);

        factory('App\RevenueLine')->states('lcs-full')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now()->subYears(3),
        ]);

        return $revenue->fresh();
    }

    protected function createEmptyAllYearRevenueLines($listing)
    {
        $revenue = factory('App\Revenue')->states('lcs-empty')->create(['listing_id' => $listing->id]);

        factory('App\RevenueLine')->states('lcs-empty')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now(),
        ]);

        factory('App\RevenueLine')->states('lcs-empty')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now()->subYears(1),
        ]);

        factory('App\RevenueLine')->states('lcs-empty')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now()->subYears(2),
        ]);

        factory('App\RevenueLine')->states('lcs-empty')->create([
            'revenue_id' => $revenue->id,
            'year' => Carbon::now()->subYears(3),
        ]);

        return $revenue->fresh();
    }

    protected function createFullAllYearExpenseLines($listing)
    {
        $expense = factory('App\Expense')->states('lcs-full')->create(['listing_id' => $listing->id]);

        factory('App\ExpenseLine')->states('lcs-full')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year->subYears(2),
        ]);

        factory('App\ExpenseLine')->states('lcs-full')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year->subYears(1),
        ]);

        factory('App\ExpenseLine')->states('lcs-full')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year,
        ]);

        factory('App\ExpenseLine')->states('lcs-full')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year->addYear(),
        ]);

        return $expense->fresh();
    }

    protected function createEmptyAllYearExpenseLines($listing)
    {
        $expense = factory('App\Expense')->states('lcs-empty')->create(['listing_id' => $listing->id]);

        factory('App\ExpenseLine')->states('lcs-empty')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year->subYears(1),
        ]);

        factory('App\ExpenseLine')->states('lcs-empty')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year->subYears(2),
        ]);

        factory('App\ExpenseLine')->states('lcs-empty')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year,
        ]);

        factory('App\ExpenseLine')->states('lcs-empty')->create([
            'expense_id' => $expense->id,
            'year' => $listing->hf_most_recent_year->addYear(),
        ]);

        return $expense->fresh();
    }

    protected function assertRepeaterSection($listing, $points, $section, $total = null)
    {
        $total = is_null($total) ? $points : $total;
        $percentage = ($total <= 0) ? 0 : ($total/$points) * 100;
        $totalPercentage = ($total <= 0) ? 0 : ($total/60);
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $this->assertEquals(round($totalPercentage, 2), round($financial->totalPercentage(), 2));
        $this->assertEquals($total, $financial->sectionTotal($section));
        $this->assertEquals(round($percentage), $financial->sectionPercentageForDisplay($section));
    }
}
