<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasHistoricalFinancials;
use App\Support\HistoricalFinancial\FormatsPercentages;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class HistoricalFinancial extends BaseModel
{
    use HasHistoricalFinancials, HasYearlyDataHelpers;
    use FormatsPercentages;

    public $fillable = [
        'listing_id',
        'year',
        'number_transactions',
        'number_customers',
        'employee_wages_benefits',
        'employee_education_training',
        'contractor_expenses',
        'utilities',
        'rent_lease_expenses',
        'office_supplies',
        'cost_goods_sold',
        'transportation',
        'meals_entertainment',
        'travel_expenses',
        'professional_services',
        'interest_expense',
        'depreciation',
        'amortization',
        'general_operational_expenses',
        'business_taxes',
        'capital_expenditures',
        'stock_based_compensation',
        'change_working_capital',
        'cash_equivalents',
        'investments',
        'accounts_receivable',
        'inventory',
        'prepaid_expenses',
        'other_current_assets',
        'property_plant_equipment',
        'goodwill',
        'intangible_assets',
        'other_assets',
        'accounts_payable',
        'current_debt',
        'accrued_liabilities',
        'unearned_revenues',
        'other_current_liabilities',
        'long_term_debt',
        'deferred_income_taxes',
        'deferred_rent_expense',
        'other_liabilities',
        'paid_in_capital',
        'retained_earnings',
        'other_equity_accounts',
    ];

    public $casts = [
        'listing_id' => 'integer',
        'year' => 'date',
        'number_transactions' => 'integer',
        'number_customers' => 'integer',
        'employee_wages_benefits' => 'integer',
        'employee_education_training' => 'integer',
        'contractor_expenses' => 'integer',
        'utilities' => 'integer',
        'rent_lease_expenses' => 'integer',
        'office_supplies' => 'integer',
        'cost_goods_sold' => 'integer',
        'transportation' => 'integer',
        'meals_entertainment' => 'integer',
        'travel_expenses' => 'integer',
        'professional_services' => 'integer',
        'interest_expense' => 'integer',
        'depreciation' => 'integer',
        'amortization' => 'integer',
        'general_operational_expenses' => 'integer',
        'business_taxes' => 'integer',
        'capital_expenditures' => 'integer',
        'stock_based_compensation' => 'integer',
        'change_working_capital' => 'integer',
        'cash_equivalents' => 'integer',
        'investments' => 'integer',
        'accounts_receivable' => 'integer',
        'inventory' => 'integer',
        'prepaid_expenses' => 'integer',
        'other_current_assets' => 'integer',
        'property_plant_equipment' => 'integer',
        'goodwill' => 'integer',
        'intangible_assets' => 'integer',
        'other_assets' => 'integer',
        'accounts_payable' => 'integer',
        'current_debt' => 'integer',
        'accrued_liabilities' => 'integer',
        'unearned_revenues' => 'integer',
        'other_current_liabilities' => 'integer',
        'long_term_debt' => 'integer',
        'deferred_income_taxes' => 'integer',
        'deferred_rent_expense' => 'integer',
        'other_liabilities' => 'integer',
        'paid_in_capital' => 'integer',
        'retained_earnings' => 'integer',
        'other_equity_accounts' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['listingSpaces'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['listingSpaces'];

    public function listingSpaces()
    {
        return $this->listing->spaces();
    }

    public function listing()
    {
        return $this->belongsTo('App\Listing');
    }

    public function getRevenuesSumAttribute()
    {
        return $this->listing->revenues->map(function ($revenue) {
            return $revenue->lines->filter(function ($line) {
                return intval($line->year->format('Y')) === intval($this->year->format('Y'));
            })->pluck('amount')->sum();
        })->sum();
    }

    public function getAdjustedExpensesSumAttribute()
    {
        return $this->listing->expenses->map(function ($expense) {
            return $expense->lines->filter(function ($line) {
                return intval($line->year->format('Y')) === intval($this->year->format('Y'));
            })->pluck('amount')->sum();
        })->sum();
    }

    public function getTotalExpensesAttribute()
    {
        return array_sum([
            $this->cost_goods_sold,
            $this->employee_wages_benefits,
            $this->transportation,
            $this->contractor_expenses,
            $this->employee_education_training,
            $this->meals_entertainment,
            $this->travel_expenses,
            $this->office_supplies,
            $this->professional_services,
            $this->utilities,
            $this->rent_lease_expenses,
            $this->depreciation,
            $this->amortization,
            $this->interest_expense,
            $this->general_operational_expenses,
        ]);
    }

    public function getEarningsBeforeTaxesAttribute()
    {
        return $this->revenues_sum - $this->total_expenses;
    }

    public function getNetEarningsAttribute()
    {
        return $this->earnings_before_taxes - $this->business_taxes;
    }

    public function getTotalCurrentAssetsAttribute()
    {
        return array_sum([
            $this->cash_equivalents,
            $this->investments,
            $this->accounts_receivable,
            $this->inventory,
            $this->prepaid_expenses,
            $this->other_current_assets,
        ]);
    }

    public function getTotalAssetsAttribute()
    {
        return array_sum([
            $this->total_current_assets,
            $this->goodwill,
            $this->intangible_assets,
            $this->property_plant_equipment,
            $this->other_assets,
        ]);
    }

    public function getTotalCurrentLiabilitiesAttribute()
    {
        return array_sum([
            $this->accounts_payable,
            $this->current_debt,
            $this->accrued_liabilities,
            $this->unearned_revenues,
            $this->other_current_liabilities,
        ]);
    }

    public function getTotalLiabilitiesAttribute()
    {
        return array_sum([
            $this->total_current_liabilities,
            $this->long_term_debt,
            $this->deferred_income_taxes,
            $this->deferred_rent_expense,
            $this->other_liabilities,
        ]);
    }

    public function getTotalShareholdersEquityAttribute()
    {
        return array_sum([
            $this->paid_in_capital,
            $this->retained_earnings,
            $this->other_equity_accounts,
        ]);
    }

    public function getTotalLiabilitiesShareholdersEquityAttribute()
    {
        return array_sum([
            $this->total_liabilities,
            $this->total_shareholders_equity,
        ]);
    }

    public function getPreviousYearFinancialAttribute()
    {
        $inRange = $this->yearRange()->contains($this->year->subYear()->format('Y'));

        return HistoricalFinancial::where([
            'listing_id' => $this->listing_id,
            'year' => $this->year->subYear(),
        ])->first();
    }

    /**
     * Gets percent revenue
     *
     * @param float $amount
     * @return float
     */
    public function getPercentRevenue($amount)
    {
        $amount = floatval($amount);
        if ($amount <= 0 || $this->revenues_sum <= 0) {
            return $this->formatPercentage(0);
        }

        $percent = ($amount / $this->revenues_sum);

        return $this->formatPercentage($percent * 100);
    }

    public function getIsCurrentYearAttribute()
    {
        return intval($this->year->format('Y')) === intval(date('Y'));
    }

    public function getPercentGrowth($attribute)
    {
        if ($this->isFinalYear()) {
            return 'N/A';
        }

        $currentValue = floatval(optional($this)->$attribute);
        $previousValue = floatval(optional($this->previous_year_financial)->$attribute);

        if (0 >= $previousValue) {
            return 'N/A';
        }

        $value = $currentValue / $previousValue - 1;

        return $this->formatPercentage($value * 100);
    }

    public function getPercentMargin($amount)
    {
        $amount = floatval($amount);
        if ($amount <= 0) {
            return $this->formatPercentage(0);
        }

        return $this->formatPercentage(($amount / $this->revenues_sum) * 100);
    }

    public function getPercentEbitda($amount)
    {
        $amount = floatval($amount);
        if ($amount <= 0 || $this->adjusted_ebitda <= 0) {
            return $this->formatPercentage(0);
        }

        return $this->formatPercentage(($amount / $this->adjusted_ebitda) * 100);
    }

    public function getPercentPreTaxIncome($amount)
    {
        $amount = floatval($amount);
        if ($amount <= 0) {
            return $this->formatPercentage(0);
        }

        $percentage = $amount / $this->earnings_before_taxes;

        return $this->formatPercentage($percentage * 100);
    }

    public function isFinalYear()
    {
        return $this->listing->isHfFinalYear(
            optional($this->year)->format('Y')
        );
    }
}
