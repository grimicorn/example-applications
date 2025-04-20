<?php

namespace App\Support;

trait HasHistoricalFinancials
{
    public function getSgaExpensesAttribute()
    {
        if (
            isset($this->transportation) ||
            isset($this->meals_entertainment) ||
            isset($this->travel_expenses) ||
            isset($this->professional_services) ||
            isset($this->general_operational_expens)
        ) {
            return array_sum([
                $this->transportation,
                $this->meals_entertainment,
                $this->travel_expenses,
                $this->professional_services,
                $this->general_operational_expenses
            ]);
        }
    }

    public function getEmployeeExpensesAttribute()
    {
        if (
            isset($this->employee_wages_benefits) ||
            isset($this->employee_education_training) ||
            isset($this->contractor_expenses)
        ) {
            return array_sum([
                $this->employee_wages_benefits,
                $this->contractor_expenses,
                $this->employee_education_training
            ]);
        }
    }

    public function getOfficeExpensesAttribute()
    {
        if (
            isset($this->utilities) ||
            isset($this->rent_lease_expenses) ||
            isset($this->office_supplies)
        ) {
            return array_sum([
                $this->office_supplies,
                $this->rent_lease_expenses,
                $this->utilities
            ]);
        }
    }

    public function getTotalOperatingExpensesAttribute()
    {
        return array_sum([
            $this->cost_goods_sold,
            $this->s_g_a_expenses,
            $this->employee_expenses,
            $this->office_expenses
        ]);
    }

    public function getAdjustedEbitdaAttribute()
    {
        return array_sum([
            ($this->revenues_sum - $this->total_operating_expenses),
            $this->adjusted_expenses_sum,
            $this->stock_based_compensation,
        ]);
    }


    public function getAdjustedPreTaxEarningsAttribute()
    {
        return $this->adjusted_ebitda - $this->interest_expense - $this->amortization - $this->depreciation;
    }

    public function getDiscretionaryCashFlowAttribute()
    {
        return array_sum([
            ($this->adjusted_ebitda - $this->capital_expenditures),
            $this->change_working_capital
        ]);
    }


    public function getDiscretionaryCashFlowEbitdaPercentAttribute()
    {
        return number_format(
            ($this->discretionary_cash_flow / $this->adjusted_ebitda) * 100,
            1
        );
    }

    public function getNetCashFlowAttribute()
    {
        return $this->discretionary_cash_flow - $this->interest_expense - $this->business_taxes;
    }
}
