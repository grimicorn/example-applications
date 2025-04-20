<?php

namespace Tests\Support;

use App\Listing;
use App\HistoricalFinancial;

class TestHistoricalFinacialData
{
    use HasFinancialTestDataHelpers;

    /**
     * @param integer $yearStart
     */
    public function __construct($yearStart = null)
    {
        $this->setYears($yearStart);
    }

    /**
     * Gets the attributes for the revenue section.
     *
     * @param int $year
     * @return array
     */
    public function revenueAttributesForYear($year)
    {
        $attributes = $this->getAttributesByYear([
            'revenue_source_1' => [ 80000, 100000, 110000, 60000 ],
            'revenue_source_2' => [ 95000, 120000, 135000, 75000 ],
            'revenue_source_3' => [ 0, 0, 0, 0 ],
            'revenue_source_4' => [ 0, 0, 0, 0 ],
        ], $year, $listing = null);

        // Remove the items required for HistoricalFinancial creation.
        unset($attributes['listing_id']);
        unset($attributes['year']);

        return $attributes;
    }

    /**
     * Gets the totals for the revenue section.
     *
     * @param int $year
     * @return array
     */
    public function revenueTotalByYear($year)
    {
        return $this->getTotalByYear([
            175000, 220000, 245000, 135000
        ], $year);
    }

    /**
     * Gets the attributes for the total expenses section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function totalExpenseAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'cost_goods_sold' => [ 46500, 54000, 59500, 32100 ],
            'employee_wages_benefits' => [ 22000, 23000, 24000, 12500 ],
            'transportation' => [ 1000, 1000, 1000, 500 ],
            'contractor_expenses' => [ 500, 500, 500, 250 ],
            'employee_education_training' => [ 2000, 2000, 2000, 1000 ],
            'meals_entertainment' => [ 3800, 3500, 3750, 2000 ],
            'travel_expenses' => [ 1000, 1000, 1000, 500 ],
            'office_supplies' => [ 250, 250, 250, 125 ],
            'professional_services' => [ 400, 400, 400, 200 ],
            'utilities' => [ 1200, 1200, 1200, 600 ],
            'rent_lease_expenses' => [ 11000, 12000, 13000, 7000 ],
            'depreciation' => [ 1200, 1300, 1400, 750 ],
            'amortization' => [ 0, 0, 0, 0 ],
            'interest_expense' => [ 2000, 2000, 2000, 1000 ],
            'general_operational_expenses' => [ 500, 500, 500, 250 ],
        ], $year, $listing);
    }

    /**
     * Gets the totals for the total expense section.
     *
     * @param int $year
     * @return array
     */
    public function totalExpenseTotalByYear($year)
    {
        return $this->getTotalByYear([
            93350, 102650, 110500, 58775,
        ], $year);
    }

    /**
     * Gets the totals for the Earnings Before Income Taxes section.
     *
     * @param int $year
     * @return array
     */
    public function earningsBeforeIncomeTaxesTotalByYear($year)
    {
        return $this->getTotalByYear([
            81650, 117350, 134500, 76225,
        ], $year);
    }

    /**
     * Gets the attributes for the Net Earnings section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function netEarningsAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'business_taxes' => [ 28000, 40000, 44000, 24000 ],
        ], $year, $listing);
    }


    /**
     * Gets the totals for the Net Earnings section.
     *
     * @param int $year
     * @return array
     */
    public function netEarningsTotalByYear($year)
    {
        return $this->getTotalByYear([
            53650, 77350, 90500, 52225,
        ], $year);
    }

    /**
     * Gets the attributes for the Total Current section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function totalCurrentAssetsAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'cash_equivalents' => [ 10535, 15470, 21920, 25543 ],
            'investments' => [ 11000, 11500, 12000, 12500 ],
            'accounts_receivable' => [ 4750, 6000, 6750, 7500 ],
            'inventory' => [ 15000, 16000, 17500, 18000 ],
            'prepaid_expenses' => [ 1000, 1500, 1500, 1550 ],
            'other_current_assets' => [ 2000, 2000, 2000, 2000 ],
        ], $year, $listing);
    }


    /**
     * Gets the totals for the Total Current Assets section.
     *
     * @param int $year
     * @return array
     */
    public function totalCurrentAssetsTotalByYear($year)
    {
        return $this->getTotalByYear([
            44285, 52470, 61670, 67093,
        ], $year);
    }

    /**
     * Gets the attributes for the Total Assets section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function totalAssetsAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'goodwill' => [ 0, 0, 0, 0 ],
            'intangible_assets' => [ 0, 0, 0, 0 ],
            'property_plant_equipment' => [ 50350, 50550, 50750, 50850 ],
            'other_assets' => [ 5000, 5000, 5000, 5000 ],
        ], $year, $listing);
    }

    /**
     * Gets the totals for the Total Assets section.
     *
     * @param int $year
     * @return array
     */
    public function totalAssetsTotalByYear($year)
    {
        return $this->getTotalByYear([
            99635, 108020, 117420, 122943,
        ], $year);
    }

    /**
     * Gets the attributes for the Total Current Liabilities section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function totalCurrentLiabilitiesAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'accounts_payable' => [ 7400, 7800, 8000, 8200 ],
            'current_debt' => [ 0, 0, 0, 0 ],
            'accrued_liabilities' => [ 1500, 1750, 1900, 2000 ],
            'unearned_revenues' => [ 500, 500, 500, 500 ],
            'other_current_liabilities' => [ 1000, 1000, 1000, 1000 ],
        ], $year, $listing);
    }


    /**
     * Gets the totals for the Total Current Liabilities section.
     *
     * @param int $year
     * @return array
     */
    public function totalCurrentLiabilitiesTotalByYear($year)
    {
        return $this->getTotalByYear([
            10400, 11050, 11400, 11700,
        ], $year);
    }

    /**
     * Gets the attributes for the Total Liabilities section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function totalLiabilitiesAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'long_term_debt' => [ 40000, 40000, 40000, 40000 ],
            'deferred_income_taxes' => [ 0, 0, 0, 0 ],
            'deferred_rent_expense' => [ 1000, 1000, 1000, 1000 ],
            'other_liabilities' => [ 2000, 2000, 2000, 2000 ],
        ], $year, $listing);
    }


    /**
     * Gets the totals for the Total Liabilities section.
     *
     * @param int $year
     * @return array
     */
    public function totalLiabilitiesTotalByYear($year)
    {
        return $this->getTotalByYear([
            53400, 54050, 54400, 54700,
        ], $year);
    }

    /**
     * Gets the attributes for the Total Shareholders Equity section.
     *
     * @param App\Listing $listing
     * @param int $year
     * @return array
     */
    public function totalShareholdersEquityAttributesForYear(Listing $listing, $year)
    {
        return $this->getAttributesByYear([
            'paid_in_capital' => [ 25000, 25000, 25000, 25000 ],
            'retained_earnings' => [ 21235, 28970, 38020, 43243 ],
            'other_equity_accounts' => [ 0, 0, 0, 0 ],
        ], $year, $listing);
    }


    /**
     * Gets the totals for the Total Shareholders Equity section.
     *
     * @param int $year
     * @return array
     */
    public function totalShareholdersEquityTotalByYear($year)
    {
        return $this->getTotalByYear([
            46235, 53970, 63020, 68243,
        ], $year);
    }

    /**
     * Gets the totals for the Total Liabilities And Shareholders Equity section.
     *
     * @param int $year
     * @return array
     */
    public function totalLiabilitiesAndShareholdersEquityTotalByYear($year)
    {
        return $this->getTotalByYear([
            99635, 108020, 117420, 122943,
        ], $year);
    }

    protected function expenseAttributesByYear()
    {
        $attributes = $this->getAttributesByYear([
            'expense_source_1' => [ 0, 0, 0, 0 ],
            'expense_source_2' => [ 0, 0, 0, 0 ],
            'expense_source_3' => [ 1500, 1500, 1500, 750 ],
            'expense_source_4' => [ 0, 0, 0, 0 ],
            'expense_source_5' => [ 0, 0, 0, 0 ],
        ], $year, $listing = null);

        // Remove the items required for HistoricalFinancial creation.
        unset($attributes['listing_id']);
        unset($attributes['year']);

        return $attributes;
    }
}
