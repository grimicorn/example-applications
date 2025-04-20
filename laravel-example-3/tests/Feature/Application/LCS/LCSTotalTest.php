<?php

namespace Tests\Feature\Application\LCS;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\ListingCompletionScore\ListingCompletionScore;

/**
 * @group lcs
 * @codingStandardsIgnoreFile
 */
class LCSTotalTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_penalizes_a_user_with_a_custom_penalty()
    {
        $listing = $this->createFullListing();

        // Check that pre-penalty the listing completion score is 100%;
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;
        $this->assertEquals(115, $overview->total());
        $this->assertEquals(60, $financial->total());
        $this->assertEquals(175, $lcs->total());
        $this->assertEquals(1, $overview->totalPercentage());
        $this->assertEquals(1, $financial->totalPercentage());
        $this->assertEquals(1, $lcs->totalPercentage());

        // Lets give them a random penalty between 1 and 100
        $penalty = $this->faker->numberBetween(1, 100);
        $penaltyPercentage = $penalty / 100;
        $lcsTotal = $listing->listingCompletionScoreTotal;
        $lcsTotal->custom_penalty = $penalty;
        $lcsTotal->save();

        // Check that post penalty the listing completion score is 100% minus the penalty percentage;
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;

        $this->assertEquals(115 - (115 * $penaltyPercentage), $overview->total());
        $this->assertEquals(60 - (60 * $penaltyPercentage), $financial->total());
        $this->assertEquals(175 - (175 * $penaltyPercentage), $lcs->total());
        $this->assertEquals(1 - (1 * $penaltyPercentage), $overview->totalPercentage());
        $this->assertEquals(1 - (1 * $penaltyPercentage), $financial->totalPercentage());
        $this->assertEquals(1 - (1 * $penaltyPercentage), $lcs->totalPercentage());
    }

    /**
    * @test
    */
    public function it_penalizes_a_user_with_a_fresh_data_penalty()
    {
        $emptyListing = $this->createFullListing();
        $emptyListing->hf_most_recent_year = now()->subYear();
        $emptyListing->save();

        // Check that pre-penalty the listing completion score is 100%;
        $lcs = new ListingCompletionScore($emptyListing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;
        $this->assertEquals(115, $overview->total());
        $this->assertEquals(60, $financial->total());
        $this->assertEquals(175, $lcs->total());
        $this->assertEquals(1, $overview->totalPercentage());
        $this->assertEquals(1, $financial->totalPercentage());
        $this->assertEquals(1, $lcs->totalPercentage());

        // Set the listings Historical financials most recent year to 3 years ago.
        // This is the start of the stale data time.
        $listing = $this->createFullListing(Carbon::now()->subYears(3)->format('Y'));

        // Check that post penalty the listing completion score is 100% minus the penalty percentage;
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;
        $penaltyPercentage = .5;

        $overviewPossible = 115;
        $overviewTotal = $overviewPossible;
        $overviewPercentage = 1;
        $financialPossible = 60;
        $financialTotal = $financialPossible - ($financialPossible * $penaltyPercentage); // Only financial should recieve this penalty
        $financialPercentage = 1 - (1 * $penaltyPercentage); // Only financial should recieve this penalty
        $overviewFinancialPossible = $overviewPossible + $financialPossible;
        $overviewFinancialTotal = $overviewFinancialPossible - ($financialPossible * $penaltyPercentage); // Only financial should recieve this penalty
        $overviewFinancialPercentage = $overviewFinancialTotal / $overviewFinancialPossible;
        $this->assertEquals($overviewTotal, $overview->total());
        $this->assertEquals($financialTotal, $financial->total());
        $this->assertEquals($overviewFinancialTotal, $lcs->total());
        $this->assertEquals($overviewPercentage, $overview->totalPercentage());
        $this->assertEquals($financialPercentage, $financial->totalPercentage());
        $this->assertEquals($overviewFinancialPercentage, $lcs->totalPercentage());
    }

    /**
    * @test
    */
    public function it_penalizes_a_user_with_a_custom_penalty_and_fresh_data_penalty()
    {
        $emptyListing = $this->createFullListing();

        // Check that pre-penalty the listing completion score is 100%;
        $lcs = new ListingCompletionScore($emptyListing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;
        $this->assertEquals(115, $overview->total());
        $this->assertEquals(60, $financial->total());
        $this->assertEquals(175, $lcs->total());
        $this->assertEquals(1, $overview->totalPercentage());
        $this->assertEquals(1, $financial->totalPercentage());
        $this->assertEquals(1, $lcs->totalPercentage());

        // Create a listing that will have the stale data penalty
        $listing = $this->createFullListing(Carbon::now()->subYears(3)->format('Y'));

        // Lets give them a random penalty between 1 and 100
        $penalty = $this->faker->numberBetween(1, 100);
        $customPercentage = $penalty / 100;
        $lcsTotal = $listing->listingCompletionScoreTotal;
        $lcsTotal->custom_penalty = $penalty;
        $lcsTotal->save();

        // Set the listings Historical financials most recent year to 3 years ago.
        // This is the start of the stale data time.
        $listing->hf_most_recent_year = Carbon::now()->subYears(3);
        $listing->save();
        $stalePercentage = .5;

        // Check that post penalty the listing completion score is 100% minus the penalty percentage;
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;

        // Calculate penalty totals and percentages.
        // Only financial should recieve both penalties
        $overviewPossible = 115;
        $overviewTotalPoints = $overviewPossible - ($overviewPossible * $customPercentage);
        $overviewTotalPoints = ($overviewTotalPoints < 0) ? 0 : $overviewTotalPoints;
        $financialPossible = 60;
        $financialTotalPoints = $financialPossible - ($financialPossible * $customPercentage) - ($financialPossible * $stalePercentage);
        $financialTotalPoints = ($financialTotalPoints < 0) ? 0 : $financialTotalPoints;
        $totalPoints = $overviewTotalPoints + $financialTotalPoints;
        $totalPoints = ($totalPoints < 0) ? 0 : $totalPoints;
        $overviewPercentage = 1 - (1 * $customPercentage);
        $overviewPercentage = ($overviewPercentage < 0) ? 0 : $overviewPercentage;
        $financialPercentage = 1 - (1 * $customPercentage) - (1 * $stalePercentage);
        $financialPercentage = ($financialPercentage < 0) ? 0 : $financialPercentage;
        $totalPercentage = $totalPoints / ($overviewPossible + $financialPossible);
        $totalPercentage = ($totalPercentage < 0) ? 0 : $totalPercentage;

        // Assert penalty totals and percentages
        $this->assertEquals($overviewTotalPoints, $overview->total());
        $this->assertEquals($financialTotalPoints, $financial->total());
        $this->assertEquals($totalPoints, $lcs->total());
        $this->assertEquals($overviewPercentage, $overview->totalPercentage());
        $this->assertEquals($financialPercentage, $financial->totalPercentage());
        $this->assertEquals($totalPercentage, $lcs->totalPercentage());
    }

    /**
    * @test
    */
    public function it_gives_full_credit_for_historical_financials_entered_as_all_zeros()
    {
        $listing = $this->createFullListing(null, 'lcs-hsf-zero');
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;

        $this->assertEquals(5, $financial->sectionTotal('sources_of_income'));
        $this->assertEquals(1, $financial->sectionPercentage('sources_of_income'));
        $this->assertEquals(5, $financial->sectionTotal('employee_related_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('employee_related_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('office_related_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('office_related_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('selling_general_and_administrative_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('selling_general_and_administrative_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('finance_related_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('finance_related_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('other_cash_flow_items'));
        $this->assertEquals(1, $financial->sectionPercentage('other_cash_flow_items'));
        $this->assertEquals(5, $financial->sectionTotal('non_recurring_personal_or_extra_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('non_recurring_personal_or_extra_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_recurring_assets'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_recurring_assets'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_long_term_assets'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_long_term_assets'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_current_liabilities'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_current_liabilities'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_long_term_liabilities'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_long_term_liabilities'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_shareholders_equity'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_shareholders_equity'));

        // Check that the totals are 100%.
        $this->assertEquals(60, $financial->total());
        $this->assertEquals(1, $financial->totalPercentage());
    }

    /**
    * @test
    */
    public function it_calculates_the_listing_completion_score_total()
    {
        $listing = $this->createFullListing();
        $lcs = new ListingCompletionScore($listing->fresh());
        $financial = $lcs->historicalFinancialCalculations;
        $overview = $lcs->businessOverviewCalculations;

        // Check that listing completion score is 100% for all Business Overview sections.
        $this->assertEquals(16, $overview->sectionTotal('basics'));
        $this->assertEquals(1, $overview->sectionPercentage('basics'));
        $this->assertEquals(6, $overview->sectionTotal('more_about_the_business'));
        $this->assertEquals(1, $overview->sectionPercentage('more_about_the_business'));
        $this->assertEquals(50, $overview->sectionTotal('financial_details'));
        $this->assertEquals(1, $overview->sectionPercentage('financial_details'));
        $this->assertEquals(20, $overview->sectionTotal('business_details'));
        $this->assertEquals(1, $overview->sectionPercentage('business_details'));
        $this->assertEquals(20, $overview->sectionTotal('transaction_considerations'));
        $this->assertEquals(1, $overview->sectionPercentage('transaction_considerations'));
        $this->assertEquals(3, $overview->sectionTotal('uploads'));
        $this->assertEquals(1, $overview->sectionPercentage('uploads'));

        // Check that listing completion score is 100% for all Historical Financial sections.
        $this->assertEquals(5, $financial->sectionTotal('sources_of_income'));
        $this->assertEquals(1, $financial->sectionPercentage('sources_of_income'));
        $this->assertEquals(5, $financial->sectionTotal('employee_related_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('employee_related_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('office_related_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('office_related_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('selling_general_and_administrative_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('selling_general_and_administrative_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('finance_related_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('finance_related_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('other_cash_flow_items'));
        $this->assertEquals(1, $financial->sectionPercentage('other_cash_flow_items'));
        $this->assertEquals(5, $financial->sectionTotal('non_recurring_personal_or_extra_expenses'));
        $this->assertEquals(1, $financial->sectionPercentage('non_recurring_personal_or_extra_expenses'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_recurring_assets'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_recurring_assets'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_long_term_assets'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_long_term_assets'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_current_liabilities'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_current_liabilities'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_long_term_liabilities'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_long_term_liabilities'));
        $this->assertEquals(5, $financial->sectionTotal('balance_sheet_shareholders_equity'));
        $this->assertEquals(1, $financial->sectionPercentage('balance_sheet_shareholders_equity'));

        // Check that the overall totals are 100%.
        $this->assertEquals(115, $overview->total());
        $this->assertEquals(60, $financial->total());
        $this->assertEquals(175, $lcs->total());
        $this->assertEquals(1, $overview->totalPercentage());
        $this->assertEquals(1, $financial->totalPercentage());
        $this->assertEquals(1, $lcs->totalPercentage());
    }

    /**
     * Creates a listing with a 100% completion score
     *
     * @param int $most_recent_year
     * @param string $lcs_state
     *
     * @return void
     */
    protected function createFullListing($most_recent_year = null, $lcs_state = 'lcs-full')
    {
        // Get the year range
        if (is_null($most_recent_year)) {
            $years = collect([
                'year1' => Carbon::now()->subYear()->subYears(2),
                'year2' => Carbon::now()->subYear()->subYears(1),
                'year3' => $most_recent_year = Carbon::now()->subYear(),
                'year4' => Carbon::now(),
            ]);
        } else {
            $years = collect([
                'year1' => Carbon::createFromDate($most_recent_year, 1, 1, null)->subYears(2),
                'year2' => Carbon::createFromDate($most_recent_year, 1, 1, null)->subYears(1),
                'year3' => Carbon::createFromDate($most_recent_year, 1, 1, null),
                'year4' => Carbon::createFromDate($most_recent_year, 1, 1, null)->addYear(),
            ]);
        }

        // Create a Listing
        $listing = factory('App\Listing')->states($lcs_state)->create([
            'hf_most_recent_year' => $years->get('year3'),
            'hf_most_recent_quarter' => 1, // This will stop "None Available" from messing with the score
            'year_established' => now()->subYears(20), // This will stop prior to Year established from taking effect
        ]);
        $listing->addMediaFromUrl('http://via.placeholder.com/350x150')
        ->toMediaCollection('photos');

        // Create Historical Financials for the last 4 years.
        $years->each(function ($year) use ($listing, $lcs_state) {
            factory('App\HistoricalFinancial')->states($lcs_state)->create([
                'listing_id' => $listing->id,
                'year' => $year,
            ]);
        });

        // Create Revenue for the last 4 years.
        $revenue = factory('App\Revenue')->states($lcs_state)->create(['listing_id' => $listing->id]);
        $years->each(function ($year) use ($revenue, $lcs_state) {
            factory('App\RevenueLine')->states($lcs_state)->create([
                'revenue_id' => $revenue->id,
                'year' => $year,
            ]);
        });

        // Create Expense for the last 4 years.
        $expense = factory('App\Expense')->states($lcs_state)->create(['listing_id' => $listing->id]);
        $years->each(function ($year) use ($expense, $lcs_state) {
            factory('App\ExpenseLine')->states($lcs_state)->create([
                'expense_id' => $expense->id,
                'year' => $year,
            ]);
        });

        // Save the listing completion score so we can add a penaltys to it or whatever else needed.
        (new ListingCompletionScore($listing->fresh()))->save();

        return $listing->fresh();
    }
}
