<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Tests\Support\TestHistoricalFinacialData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

// @codingStandardsIgnoreStart
class HistoricalFinacialCalculationsTest extends TestCase
{
    use RefreshDatabase;
    use HasYearlyDataHelpers;

    /**
    * @test
    */
    public function it_calculates_adjusted_ebitda()
    {
        $financials = $this->yearRange(2017)->map(function ($value) {
            $financial = factory('App\HistoricalFinancial')->states('current_year')->create([
                'year' => $year = Carbon::createFromDate($value),
                'cost_goods_sold' => $value,
                'transportation' => $value,
                'meals_entertainment' => $value,
                'travel_expenses' => $value,
                'professional_services' => $value,
                'general_operational_expenses' => $value,
                'employee_wages_benefits' => $value,
                'contractor_expenses' => $value,
                'employee_education_training' => $value,
                'office_supplies' => $value,
                'rent_lease_expenses' => $value,
                'utilities' => $value,
                'stock_based_compensation' => $value
            ]);

            $listingId = $financial->listing->id;
            factory('App\ExpenseLine')->create([
                'expense_id' => factory('App\Expense')->create(['listing_id' => $listingId])->id,
                'amount' => $value,
                'year' => $year,
            ]);

            factory('App\RevenueLine')->create([
                'revenue_id' => factory('App\Revenue')->create(['listing_id' => $listingId])->id,
                'amount' => $value,
                'year' => $year,
            ]);

            return $financial->fresh();
        });

        $this->assertEquals(-18135, $financials->get('year1')->adjusted_ebitda);
        $this->assertEquals(-18144, $financials->get('year2')->adjusted_ebitda);
        $this->assertEquals(-18153, $financials->get('year3')->adjusted_ebitda);
        $this->assertEquals(-18162, $financials->get('year4')->adjusted_ebitda);
    }
}
