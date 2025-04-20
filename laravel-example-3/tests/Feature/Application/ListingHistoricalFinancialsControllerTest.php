<?php

namespace Tests\Feature\Application;

use App\User;
use App\Listing;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Tests\Support\TestHistoricalFinacialData;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ListingHistoricalFinancialsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_stores_the_general_historical_financial()
    {
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([ 'user_id' => $user->id ]);
        $route = route('listing.historical-financials.update', ['id' => $listing->id]);
        $most_recent_year = $this->faker->dateTimeThisDecade()->format('Y');
        $years = (new TestHistoricalFinacialData($most_recent_year))->years;

        // Input data for all data points
        $data = $this->financialDataForSubmit($user, $listing, $years);
        $response = $this->patch($route, $data);

        // Make sure we get a successful response.
        $response->assertStatus(302);

        // Go through each year and check that it was udpated correctly.
        $financials = $listing->fresh()->historicalFinancials;

        $this->assertCount(4, $financials);
        $financials->sortBy('year')->values()->each(function ($financial, $key) use ($data, $listing, $years) {
            $yearKey = 'year' . ($key + 1);
            $testFinancial = $financial->toArray();

            // Remove the revenue item.
            unset($data[ $yearKey ]['revenue']);

            // Remove the expense item.
            unset($data[ $yearKey ]['expense']);

            // Remove a few items $data will not have and really are not important.
            unset($testFinancial['created_at']);
            unset($testFinancial['updated_at']);
            unset($testFinancial['id']);

            // Add items that should be checked to data.
            $this->assertEquals($listing->id, $financial->listing_id);
            $this->assertEquals($years[$key], $financial->year->format('Y'));
        });
    }

    /**
    * @test
    */
    public function it_stores_the_revenue_historical_financial()
    {
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([ 'user_id' => $user->id ]);
        $route = route('listing.historical-financials.update', ['id' => $listing->id]);
        $most_recent_year = $this->faker->dateTimeThisDecade()->format('Y');
        $years = (new TestHistoricalFinacialData($most_recent_year))->years;

        // Input data for all data points
        $data = $this->financialDataForSubmit($user, $listing, $years);
        $response = $this->patch($route, $data);

        // Make sure we get a successful response.
        $response->assertStatus(302);

        // Check the revenues where created with the correct names.
        $revenues = $listing->fresh()->revenues;
        $custom_names = $data['custom_name']['revenue'];
        $this->assertCount(count($custom_names), $revenues);
        $this->assertEquals($custom_names, $listing->fresh()->revenues->pluck('name')->toArray());

        // Check the revenue lines where created with correct years and amounts.
        $yearlyRevenues = collect($data)->only(['year1', 'year2', 'year3', 'year4'])->pluck('revenue');
        $revenues->each(function ($revenue) use ($yearlyRevenues, $years) {
            $lines = $revenue->lines;
            $this->assertCount($yearlyRevenues->count(), $lines);
            $this->assertEquals($yearlyRevenues->pluck($revenue->order), $lines->pluck('amount'));
            $this->assertEquals($years, $lines->pluck('year')->map->format('Y'));
        });
    }

    /**
    * @test
    * @group failing
    */
    public function it_updates_the_revenue_historical_financial()
    {
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([ 'user_id' => $user->id ]);
        $route = route('listing.historical-financials.update', ['id' => $listing->id]);
        $most_recent_year = $this->faker->dateTimeThisDecade()->format('Y');
        $years = (new TestHistoricalFinacialData($most_recent_year))->years;

        // Input data for all data points
        $data = $this->financialDataForSubmit($user, $listing, $years);

        // Create some revenue
        $response = $this->patch($route, $data);

        // "Remove" line 3 (index 2)
        unset($data['custom_name']['revenue'][2]);
        $data['custom_name']['revenue'] = array_values($data['custom_name']['revenue']);
        unset($data['year1']['revenue'][2]);
        $data['year1']['revenue'] = array_values($data['year1']['revenue']);
        unset($data['year2']['revenue'][2]);
        $data['year2']['revenue'] = array_values($data['year2']['revenue']);
        unset($data['year3']['revenue'][2]);
        $data['year3']['revenue'] = array_values($data['year3']['revenue']);
        unset($data['year4']['revenue'][2]);
        $data['year4']['revenue'] = array_values($data['year4']['revenue']);

        // Update the revenue
        $response = $this->patch($route, $data);

        // Make sure we get a successful response.
        $response->assertStatus(302);

        // Check the revenues where created with the correct names.
        $revenues = $listing->fresh()->revenues;
        $custom_names = $data['custom_name']['revenue'];
        $this->assertCount(count($custom_names), $revenues);
        $this->assertEquals($custom_names, $listing->fresh()->revenues->pluck('name')->toArray());

        // Check the revenue lines where created with correct years and amounts.
        $yearlyRevenues = collect($data)->only(['year1', 'year2', 'year3', 'year4'])->pluck('revenue');
        $revenues->each(function ($revenue) use ($yearlyRevenues, $years) {
            $lines = $revenue->lines;
            $this->assertCount($yearlyRevenues->count(), $lines);
            $this->assertEquals($yearlyRevenues->pluck($revenue->order), $lines->pluck('amount'));
            $this->assertEquals($years, $lines->pluck('year')->map->format('Y'));
        });
    }

    /**
    * @test
    * @group failing
    */
    public function it_stores_the_most_recent_year_and_quarter()
    {
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([ 'user_id' => $user->id ]);
        $route = route('listing.historical-financials.update', ['id' => $listing->id]);
        $most_recent_year = $this->faker->dateTimeThisDecade()->format('Y');
        $years = (new TestHistoricalFinacialData($most_recent_year))->years;

        $this->withoutExceptionHandling();
        // Input data for all data points
        $data = $this->financialDataForSubmit($user, $listing, $years);
        $response = $this->patch($route, $data);

        // Make sure we get a successful response.
        $response->assertStatus(302);

        // Make sure the data was saved correctly.
        $listing = $listing->fresh();
        $this->assertEquals($data['hf_most_recent_year'], $listing->hf_most_recent_year->format('Y'));
        $this->assertEquals($data['hf_most_recent_quarter'], $listing->hf_most_recent_quarter);
    }

    /**
    * @test
    * @group failing
    */
    public function it_stores_the_expense_historical_financial()
    {
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([ 'user_id' => $user->id ]);
        $route = route('listing.historical-financials.update', ['id' => $listing->id]);
        $most_recent_year = $this->faker->dateTimeThisDecade()->format('Y');
        $years = (new TestHistoricalFinacialData($most_recent_year))->years;

        $this->withoutExceptionHandling();
        // Input data for all data points
        $data = $this->financialDataForSubmit($user, $listing, $years);
        $response = $this->patch($route, $data);

        // Make sure we get a successful response.
        $response->assertStatus(302);

        // Check the expenses where created with the correct names.
        $expenses = $listing->fresh()->expenses;
        $custom_names = $data['custom_name']['expense'];
        $this->assertCount(count($custom_names), $expenses);
        $this->assertEquals($custom_names, $listing->fresh()->expenses->pluck('name')->toArray());

        // Check the expense lines where created with correct years and amounts.
        $yearlyExpenses = collect($data)->only(['year1', 'year2', 'year3', 'year4'])->pluck('expense');
        $expenses->each(function ($expense) use ($yearlyExpenses, $years) {
            $lines = $expense->lines;
            $this->assertCount($yearlyExpenses->count(), $lines);
            $this->assertEquals($yearlyExpenses->pluck($expense->order), $lines->pluck('amount'));
            $this->assertEquals($years, $lines->pluck('year')->map->format('Y'));
        });
    }

    /**
    * @test
    * @group failing
    */
    public function it_updates_the_expense_historical_financial()
    {
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([ 'user_id' => $user->id ]);
        $route = route('listing.historical-financials.update', ['id' => $listing->id]);
        $most_recent_year = $this->faker->dateTimeThisDecade()->format('Y');
        $years = (new TestHistoricalFinacialData($most_recent_year))->years;

        // Input data for all data points
        $data = $this->financialDataForSubmit($user, $listing, $years);

        // Create some expense
        $response = $this->patch($route, $data);

        // "Remove" line 3 (index 2)
        unset($data['custom_name']['expense'][2]);
        $data['custom_name']['expense'] = array_values($data['custom_name']['expense']);
        unset($data['year1']['expense'][2]);
        $data['year1']['expense'] = array_values($data['year1']['expense']);
        unset($data['year2']['expense'][2]);
        $data['year2']['expense'] = array_values($data['year2']['expense']);
        unset($data['year3']['expense'][2]);
        $data['year3']['expense'] = array_values($data['year3']['expense']);
        unset($data['year4']['expense'][2]);
        $data['year4']['expense'] = array_values($data['year4']['expense']);

        // Update the expense
        $response = $this->patch($route, $data);

        // Make sure we get a successful response.
        $response->assertStatus(302);

        // Check the expenses where created with the correct names.
        $expenses = $listing->fresh()->expenses;
        $custom_names = $data['custom_name']['expense'];
        $this->assertCount(count($custom_names), $expenses);
        $this->assertEquals($custom_names, $listing->fresh()->expenses->pluck('name')->toArray());

        // Check the expense lines where created with correct years and amounts.
        $yearlyExpenses = collect($data)->only(['year1', 'year2', 'year3', 'year4'])->pluck('expense');
        $expenses->each(function ($expense) use ($yearlyExpenses, $years) {
            $lines = $expense->lines;
            $this->assertCount($yearlyExpenses->count(), $lines);
            $this->assertEquals($yearlyExpenses->pluck($expense->order), $lines->pluck('amount'));
            $this->assertEquals($years, $lines->pluck('year')->map->format('Y'));
        });
    }

    protected function financialDataForSubmit(User $user, Listing $listing, Collection $years)
    {
        // General financial data
        $financialData = $this->getFinancialData($listing, $years);

        // Revenue data.
        $revenueData = $this->getRevenueData();

        // Expense data.
        $expenseData = $this->getExpenseData();

        $data = [
            'hf_most_recent_year' => $years->get(2),
            'hf_most_recent_quarter' => $this->faker->numberBetween(0, 3),

            'custom_name' => array_merge(
                $revenueData['custom_name'],
                $expenseData['custom_name']
            ),

            'year1' => array_merge(
                $financialData['year1'],
                $revenueData['year1'],
                $expenseData['year1']
            ),

            'year2' => array_merge(
                $financialData['year2'],
                $revenueData['year2'],
                $expenseData['year2']
            ),

            'year3' => array_merge(
                $financialData['year3'],
                $revenueData['year3'],
                $expenseData['year3']
            ),

            'year4' => array_merge(
                $financialData['year4'],
                $revenueData['year4'],
                $expenseData['year4']
            ),
        ];

        return $data;
    }

    protected function getRevenueData()
    {
        return  [
            'custom_name' => [
                'revenue' => [
                    0 => 'Revenue Line 1',
                    1 => 'Revenue Line 2',
                    2 => 'Revenue Line 3',
                    3 => 'Revenue Line 4',
                ],
            ],
            'year1' => [
                'revenue' => [
                    0 => 80000,
                    1 => 95000,
                    2 => 0,
                    3 => 0,
                ],
            ],
            'year2' => [
                'revenue' => [
                    0 => 100000,
                    1 => 120000,
                    2 => 0,
                    3 => 0,
                ],
            ],
            'year3' => [
                'revenue' => [
                    0 => 110000,
                    1 => 135000,
                    2 => 0,
                    3 => 0,
                ],
            ],
            'year4' => [
                'revenue' => [
                    0 => 60000,
                    1 => 75000,
                    2 => 0,
                    3 => 0,
                ],
            ],
        ];
    }

    protected function getExpenseData()
    {
        return  [
            'custom_name' => [
                'expense' => [
                    0 => 'Expense Line 1',
                    1 => 'Expense Line 2',
                    2 => 'Expense Line 3',
                    3 => 'Expense Line 4',
                    4 => 'Expense Line 5',
                ],
            ],
            'year1' => [
                'expense' => [
                    0 => 0,
                    1 => 0,
                    2 => 1500,
                    3 => 0,
                    4 => 0,
                ],
            ],
            'year2' => [
                'expense' => [
                    0 => 0,
                    1 => 0,
                    2 => 1500,
                    3 => 0,
                    4 => 0,
                ],
            ],
            'year3' => [
                'expense' => [
                    0 => 0,
                    1 => 0,
                    2 => 1500,
                    3 => 0,
                    4 => 0,
                ],
            ],
            'year4' => [
                'expense' => [
                    0 => 0,
                    1 => 0,
                    2 => 750,
                    3 => 0,
                    4 => 0,
                ],
            ],
        ];
    }

    protected function getFinancialData(Listing $listing, Collection $years)
    {
        return $years->map(function ($year) use ($listing) {
            // Build up the historical financials for each year
            return collect(factory('App\HistoricalFinancial')->make([
                'year' => $year,
                'listing_id' => $listing->id,
            ]))->filter(function ($value, $key) {
                // We will not want to send the year/listing id since this will not be there in the form.
                return !in_array($key, ['listing_id', 'year']);
            });
        })->keyBy(function ($item, $key) {
            $index = $key + 1;
            return "year{$index}";
        })->toArray();
    }
}
