<?php

namespace App\Support\HistoricalFinancial;

class TableRows
{
    use HasYearlyDataHelpers;

    protected $space;
    protected $listing;
    protected $revenues;
    protected $financials;
    protected $yearStart;

    public function __construct($space)
    {
        $this->space = $space;
        $this->listing = $space->listing;
        $this->yearStart = $this->listing->hfYearStart();
        $this->revenues = $this->listing->revenues->map(function ($revenue) {
            $revenue->lines = $this->setRevenueRelationshipIfMissing($revenue->lines, $revenue);
            if (is_null($revenue->listing->hf_most_recent_year)) {
                $revenue->listing->hf_most_recent_year = $this->listing->hf_most_recent_year;
            }

            return $revenue;
        });
        $this->expenses = $this->listing->expenses->map(function ($expense) {
            if (is_null($expense->listing->hf_most_recent_year)) {
                $expense->listing->hf_most_recent_year = $this->listing->hf_most_recent_year;
            }

            return $expense;
        });
        ;
        $this->expenseTotals = $this->listing->expenseTotals();
        $this->financials = $this->listing->historicalFinancials->filter(function ($financial) {
            return $this->yearRange()->contains($financial->year->format('Y'));
        })->sortBy(function ($financial) {
            return intval($financial->year->format('Y'));
        })->values();
    }

    /**
     * Formats the financial year header.
     *
     * @param App\HistoricalFinancial $financial
     * @return void
     */
    protected function formatFinancialYearHeader($financial)
    {
        $year = $financial->year->format('Y');
        if (!$financial->isFinalYear()) {
            return "FY {$year}";
        }

        $quarterLabel = $financial->listing->getQuarterLabel();

        return "{$quarterLabel} {$year} YTD";
    }

    /**
     * Gets the Historical Financial rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function getHistoricalFinancials()
    {
        $revenues = $this->revenues;
        $financials = $this->financials;
        return r_collect([
            'income_statement' => [
                'label' => 'Income Statement',
                'values' => $financials->map(function ($financial) {
                    return $this->formatFinancialYearHeader($financial);
                }),
            ],
            'revenues' => $revenues->filter->hasInputValues()->map(function ($revenue) {
                return [
                    'label' => $revenue->name,
                    'values' => $revenue->linesInYearRange()->map(function ($line) {
                        return price($line->amount);
                    }),
                ];
            }),
            'total_revenue' => [
                'label' => 'Total Revenue',
                'values' => $financials->map(function ($financial) {
                    return price($financial->revenues_sum);
                }),
            ],
            'total_cost_of_goods_sold' => [
                'label' => 'Cost of Goods Sold',
                'values' => $financials->map(function ($financial) {
                    return price($financial->cost_goods_sold);
                }),
            ],
            'employee_wage_and_benefits' => [
                'label' => 'Employee Wage and Benefits',
                'values' => $financials->map(function ($financial) {
                    return price($financial->employee_wages_benefits, true);
                }),
            ],
            'transportation' => [
                'label' => 'Transportation',
                'values' => $financials->map(function ($financial) {
                    return price($financial->transportation, true);
                }),
            ],
            'contractors' => [
                'label' => 'Contractor Related Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->contractor_expenses, true);
                }),
            ],
            'education_and_training' => [
                'label' => 'Employee Education and Training Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->employee_education_training, true);
                }),
            ],
            'meals_and_entertainment' => [
                'label' => 'Meals and Entertainment',
                'values' => $financials->map(function ($financial) {
                    return price($financial->meals_entertainment, true);
                }),
            ],
            'travel_expenses' => [
                'label' => 'Travel Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->travel_expenses, true);
                }),
            ],
            'office_expenses' => [
                'label' => 'Office Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->office_supplies, true);
                }),
            ],
            'professional_services' => [
                'label' => 'Professional Services',
                'values' => $financials->map(function ($financial) {
                    return price($financial->professional_services, true);
                }),
            ],
            'utilities' => [
                'label' => 'Utilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->utilities, true);
                }),
            ],
            'rent_or_lease_expense' => [
                'label' => 'Rent or Lease Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->rent_lease_expenses, true);
                }),
            ],
            'depreciation' => [
                'label' => 'Depreciation',
                'values' => $financials->map(function ($financial) {
                    return price($financial->depreciation, true);
                }),
            ],
            'amortization' => [
                'label' => 'Amortization',
                'values' => $financials->map(function ($financial) {
                    return price($financial->amortization, true);
                }),
            ],
            'interest_expense' => [
                'label' => 'Interest Expense',
                'values' => $financials->map(function ($financial) {
                    return price($financial->interest_expense, true);
                }),
            ],
            'other_general_operating_expenses' => [
                'label' => 'Other General Operating Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->general_operational_expenses, true);
                }),
            ],
            'total_expenses' => [
                'label' => 'Total Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_expenses);
                }),
            ],
            'earnings_before_income_taxes' => [
                'label' => 'Earnings before Income Taxes',
                'values' => $financials->map(function ($financial) {
                    return price($financial->earnings_before_taxes);
                }),
            ],
            'income_tax' => [
                'label' => 'Income Taxes',
                'values' => $financials->map(function ($financial) {
                    return price($financial->business_taxes, true);
                }),
            ],
            'net_earnings' => [
                'label' => 'Net Earnings',
                'values' => $financials->map(function ($financial) {
                    return price($financial->net_earnings);
                }),
            ],
            'balance_sheet' => [
                'label' => 'Balance Sheet',
                'values' => $financials->map(function ($financial) {
                    return $this->formatFinancialYearHeader($financial);
                }),
            ],

            'cash_and_cash_equivalents' => [
                'label' => 'Cash and Cash Equivalents',
                'values' => $financials->map(function ($financial) {
                    return price($financial->cash_equivalents);
                }),
            ],
            'investments' => [
                'label' => 'Investments',
                'values' => $financials->map(function ($financial) {
                    return price($financial->investments, true);
                }),
            ],
            'accounts_receivable' => [
                'label' => 'Accounts Receivable',
                'values' => $financials->map(function ($financial) {
                    return price($financial->accounts_receivable, true);
                }),
            ],
            'inventory' => [
                'label' => 'Inventory',
                'values' => $financials->map(function ($financial) {
                    return price($financial->inventory, true);
                }),
            ],
            'prepaid_expenses' => [
                'label' => 'Prepaid Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->prepaid_expenses, true);
                }),
            ],
            'other_current_assets' => [
                'label' => 'Other Current Assets',
                'values' => $financials->map(function ($financial) {
                    return price($financial->other_current_assets, true);
                }),
            ],
            'total_current_assets' => [
                'label' => 'Total Current Assets',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_current_assets);
                }),
            ],
            'goodwill' => [
                'label' => 'Goodwill',
                'values' => $financials->map(function ($financial) {
                    return price($financial->goodwill);
                }),
            ],
            'other_intangible_assets' => [
                'label' => 'Other Intangible Assets',
                'values' => $financials->map(function ($financial) {
                    return price($financial->intangible_assets, true);
                }),
            ],
            'property_plant_and_equipment' => [
                'label' => 'Property, Plant & Equipment',
                'values' => $financials->map(function ($financial) {
                    return price($financial->property_plant_equipment, true);
                }),
            ],
            'other_assets' => [
                'label' => 'Other Assets',
                'values' => $financials->map(function ($financial) {
                    return price($financial->other_assets, true);
                }),
            ],
            'total_assets' => [
                'label' => 'Total Assets',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_assets);
                }),
            ],
            'accounts_payable' => [
                'label' => 'Accounts Payable',
                'values' => $financials->map(function ($financial) {
                    return price($financial->accounts_payable);
                }),
            ],
            'short_term_and_current_debt' => [
                'label' => 'Short-Term & Current Debt',
                'values' => $financials->map(function ($financial) {
                    return price($financial->current_debt, true);
                }),
            ],
            'accrued_liabilities' => [
                'label' => 'Accrued Liabilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->accrued_liabilities, true);
                }),
            ],
            'unearned_revenues' => [
                'label' => 'Unearned Revenues',
                'values' => $financials->map(function ($financial) {
                    return price($financial->unearned_revenues, true);
                }),
            ],
            'other_current_liabilities' => [
                'label' => 'Other Current Liabilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->other_current_liabilities, true);
                }),
            ],
            'total_current_liabilities' => [
                'label' => 'Total Current Liabilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_current_liabilities);
                }),
            ],
            'long_term_debt_less_current_portion' => [
                'label' => 'Long-Term Debt, Less Current Portion',
                'values' => $financials->map(function ($financial) {
                    return price($financial->long_term_debt, true);
                }),
            ],
            'deferred_income_taxes' => [
                'label' => 'Deferred Income Taxes',
                'values' => $financials->map(function ($financial) {
                    $price = $financial->deferred_income_taxes;

                    return price($price ?:0, true);
                }),
            ],
            'deferred_rent_or_lease_expense' => [
                'label' => 'Deferred Rent or Lease Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->deferred_rent_expense, true);
                }),
            ],
            'other_liabilities' => [
                'label' => 'Other Liabilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->other_liabilities, true);
                }),
            ],
            'total_liabilities' => [
                'label' => 'Total Liabilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_liabilities);
                }),
            ],
            'paid_in_capital' => [
                'label' => 'Paid-in Capital',
                'values' => $financials->map(function ($financial) {
                    return price($financial->paid_in_capital);
                }),
            ],
            'retained_earnings' => [
                'label' => 'Retained Earnings',
                'values' => $financials->map(function ($financial) {
                    return price($financial->retained_earnings, true);
                }),
            ],
            'other_equity_accounts' => [
                'label' => 'Other Equity Accounts',
                'values' => $financials->map(function ($financial) {
                    return price($financial->other_equity_accounts, true);
                }),
            ],
            'total_shareholders_equity' => [
                'label' => 'Total Shareholders\' Equity',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_shareholders_equity);
                }),
            ],
            'total_liabilities_and_shareholders_equity' => [
                'label' => 'Total Liabilities and Shareholders\' Equity',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_liabilities_shareholders_equity);
                }),
            ],
        ]);
    }

    /**
     * Exports the Historical Financial rows
     *
     * @return void
     */
    public function exportHistoricalFinancialsCSV()
    {
        $rows = $this->getHistoricalFinancials();

        // Create a CSV to write to.
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());

        // Add the rows to the CSV.
        $rows->each(function ($row, $key) use ($csv) {
            if ($key !== 'revenues') {
                $csv->insertOne($this->mergeRowLabelValues($row));

                return;
            }

            $row->each(function ($line) use ($csv) {
                $csv->insertOne($this->mergeRowLabelValues($line));
            });
        });

        $csv->output($this->fileName('historical-financials'));
    }

    /**
     * Gets the adjusted Financials & Trends rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAdjustedFinancialsTrends()
    {
        $revenues = $this->revenues;
        $financials = $this->financials;
        $expenses = $this->expenses;
        $expenseTotals = $this->expenseTotals;
        return r_collect([
            'adjusted_historical_financial_summary' => [
                'label' => 'Adjusted Historical Financial Summary',
                'values' => $financials->map(function ($financial) {
                    return $this->formatFinancialYearHeader($financial);
                }),
            ],
            'total_revenue' => [
                'label' => 'Total Revenue',
                'values' => $financials->map(function ($financial) {
                    return price($financial->revenues_sum);
                }),
            ],
            'revenues_sum_of_growth' => [
                'label' => '% Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('revenues_sum');
                }),
            ],
            'cost_of_goods_sold_1' => [
                'label' => 'Cost of Goods Sold',
                'values' => $financials->map(function ($financial) {
                    return price($financial->cost_goods_sold);
                }),
            ],
            's_g_a_expenses_1' => [
                'label' => 'SG&A Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->s_g_a_expenses, true);
                }),
            ],
            'employee_related_expenses_1' => [
                'label' => 'Employee Related Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->employee_expenses, true);
                }),
            ],
            'office_related_expenses_1' => [
                'label' => 'Office Related Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->office_expenses, true);
                }),
            ],
            'total_operating_expenses' => [
                'label' => 'Total Operating Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->total_operating_expenses);
                }),
            ],
            'non_recurring_expense_adjustments' => [
                'label' => 'Non-Recurring Expense Adjustments',
                'values' => $financials->map(function ($financial) {
                    return price($financial->adjusted_expenses_sum);
                }),
            ],
            'adjusted_ebitda_1' => [
                'label' => 'Adjusted EBITDA',
                'values' => $financials->map(function ($financial) {
                    return price($financial->adjusted_ebitda);
                }),
            ],
            'adjusted_ebitda_of_margin' => [
                'label' => '% Margin',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentMargin($financial->adjusted_ebitda);
                }),
            ],
            'interest' => [
                'label' => 'Interest',
                'values' => $financials->map(function ($financial) {
                    return price($financial->interest_expense, true);
                }),
            ],
            'amortization_1' => [
                'label' => 'Amortization',
                'values' => $financials->map(function ($financial) {
                    return price($financial->amortization, true);
                }),
            ],
            'depreciation_1' => [
                'label' => 'Depreciation',
                'values' => $financials->map(function ($financial) {
                    return price($financial->depreciation, true);
                }),
            ],
            'adjusted_pre_tax_earnings' => [
                'label' => 'Adjusted Pre-Tax Earnings',
                'values' => $financials->map(function ($financial) {
                    return price($financial->adjusted_pre_tax_earnings);
                }),
            ],
            'adjusted_pre_tax_earnings_of_margin' => [
                'label' => '% Margin',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentMargin($financial->adjusted_pre_tax_earnings);
                }),
            ],
            'free_cash_flow_reconciliation' => [
                'label' => 'Free Cash Flow Reconciliation:',
                'values' => ['', '', '', ''],
            ],
            'adjusted_ebitda_2' => [
                'label' => 'Adjusted EBITDA',
                'values' => $financials->map(function ($financial) {
                    return price($financial->adjusted_ebitda);
                }),
            ],
            'less_capital_expenditures' => [
                'label' => 'Less: Capital Expenditures',
                'values' => $financials->map(function ($financial) {
                    return price($financial->capital_expenditures, true);
                }),
            ],
            'less_change_in_working_capital' => [
                'label' => 'Less: Change in Working Capital',
                'values' => $financials->map(function ($financial) {
                    return price($financial->change_working_capital, true);
                }),
            ],
            'plus_stock_based_compensation' => [
                'label' => 'Plus: Stock-based Compensation',
                'values' => $financials->map(function ($financial) {
                    return price($financial->stock_based_compensation, true);
                }),
            ],
            'discretionary_cash_flow' => [
                'label' => 'Discretionary Cash Flow',
                'values' => $financials->map(function ($financial) {
                    return price($financial->discretionary_cash_flow);
                }),
            ],
            'discretionary_cash_flow_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->discretionary_cash_flow);
                }),
            ],
            'discretionary_cash_flow_of_ebitda' => [
                'label' => '% of EBITDA',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentEbitda($financial->discretionary_cash_flow);
                }),
            ],
            'less_interest' => [
                'label' => 'Less: Interest',
                'values' => $financials->map(function ($financial) {
                    return price($financial->interest_expense);
                }),
            ],
            'less_cash_taxes' => [
                'label' => 'Less: Cash Taxes',
                'values' => $financials->map(function ($financial) {
                    return price($financial->business_taxes, true);
                }),
            ],
            'net_cash_flow' => [
                'label' => 'Net Cash Flow',
                'values' => $financials->map(function ($financial) {
                    return price($financial->net_cash_flow);
                }),
            ],
            'net_cash_flow_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->net_cash_flow);
                }),
            ],
            'net_cash_flow_of_ebitda' => [
                'label' => '% of EBITDA',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentEbitda($financial->net_cash_flow);
                }),
            ],
            'historical_revenue_trends' => [
                'label' => 'Historical Revenue Trends',
                'values' => $financials->map(function ($financial) {
                    return $this->formatFinancialYearHeader($financial);
                }),
            ],
            'revenues' => $revenues->filter->hasInputValues()->map(function ($revenue, $key) use ($financials) {
                // Make sure all lines have a revenue.
                $lines = $revenue->linesInYearRange();
                return [
                    'line' => [
                        'label' => $revenue->name,
                        'values' => $lines->map(function ($line) {
                            return price($line->amount);
                        }),
                    ],
                    'of_growth' => [
                        'label' => '% growth',
                        'values' => $lines->map(function ($line) {
                            return $line->getPercentGrowth();
                        }),
                    ],
                    'of_total_revenue' => [
                        'label' => '% total revenue',
                        'values' => $lines->map(function ($line, $key) use ($financials) {
                            return $financials[$key]->getPercentRevenue($line->amount);
                        }),
                    ],
                ];
            }),
            'historical_expense_trends' => [
                'label' => 'Historical Expense Trends',
                'values' => $financials->map(function ($financial) {
                    return $this->formatFinancialYearHeader($financial);
                })
            ],
            'cost_of_goods_sold_2' => [
                'label' => 'Cost of Goods Sold',
                'values' => $financials->map(function ($financial) {
                    return price($financial->cost_goods_sold);
                }),
            ],
            'cost_goods_sold_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->cost_goods_sold);
                }),
            ],
            'cost_goods_sold_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('cost_goods_sold');
                }),
            ],
            'transportation' => [
                'label' => 'Transportation',
                'values' => $financials->map(function ($financial) {
                    return price($financial->transportation);
                }),
            ],
            'transportation_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->transportation);
                }),
            ],
            'transportation_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('transportation');
                }),
            ],
            'meals_and_entertainment' => [
                'label' => 'Meals and Entertainment',
                'values' => $financials->map(function ($financial) {
                    return price($financial->meals_entertainment);
                }),
            ],
            'meals_entertainment_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->meals_entertainment);
                }),
            ],
            'meals_entertainment_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('meals_entertainment');
                }),
            ],
            'travel_expenses' => [
                'label' => 'Travel Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->travel_expenses);
                }),
            ],
            'travel_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->travel_expenses);
                }),
            ],
            'travel_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('travel_expenses');
                }),
            ],
            'professional_services' => [
                'label' => 'Professional Services',
                'values' => $financials->map(function ($financial) {
                    return price($financial->professional_services);
                }),
            ],
            'professional_services_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->professional_services);
                }),
            ],
            'professional_services_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('professional_services');
                }),
            ],
            'other_general_operating_expenses' => [
                'label' => 'Other General Operating Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->general_operational_expenses);
                }),
            ],
            'general_operational_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->general_operational_expenses);
                }),
            ],
            'general_operational_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('general_operational_expenses');
                }),
            ],
            's_g_a_expenses_2' => [
                'label' => 'SG&A Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->s_g_a_expenses);
                }),
            ],
            's_g_a_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->s_g_a_expenses);
                }),
            ],
            's_g_a_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('s_g_a_expenses');
                }),
            ],
            'employee_wages_benefits' => [
                'label' => 'Employee Wages & Benefits',
                'values' => $financials->map(function ($financial) {
                    return price($financial->employee_wages_benefits);
                }),
            ],
            'employee_wages_benefits_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->employee_wages_benefits);
                }),
            ],
            'employee_wages_benefits_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('employee_wages_benefits');
                }),
            ],
            'contractors' => [
                'label' => 'Contractors',
                'values' => $financials->map(function ($financial) {
                    return price($financial->contractor_expenses);
                }),
            ],
            'contractor_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->contractor_expenses);
                }),
            ],
            'contractor_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('contractor_expenses');
                }),
            ],
            'education_training' => [
                'label' => 'Education & Training',
                'values' => $financials->map(function ($financial) {
                    return price($financial->employee_education_training);
                }),
            ],
            'employee_education_training_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->employee_education_training);
                }),
            ],
            'employee_education_training_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('employee_education_training');
                }),
            ],
            'employee_related_expenses_2' => [
                'label' => 'Employee Related Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->employee_expenses);
                }),
            ],
            'employee_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->employee_expenses);
                }),
            ],
            'employee_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('employee_expenses');
                }),
            ],
            'office_expenses_posting' => [
                'label' => 'Office Expenses & Posting',
                'values' => $financials->map(function ($financial) {
                    return price($financial->office_supplies);
                }),
            ],
            'office_supplies_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->office_supplies);
                }),
            ],
            'office_supplies_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('office_supplies');
                }),
            ],
            'utilities' => [
                'label' => 'Utilities',
                'values' => $financials->map(function ($financial) {
                    return price($financial->utilities);
                }),
            ],
            'utilities_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->utilities);
                }),
            ],
            'utilities_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('utilities');
                }),
            ],
            'rent_or_lease_expenses' => [
                'label' => 'Rent or Lease Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->rent_lease_expenses);
                }),
            ],
            'rent_lease_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->rent_lease_expenses);
                }),
            ],
            'rent_lease_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('rent_lease_expenses');
                }),
            ],
            'office_related_expenses_2' => [
                'label' => 'Office Related Expenses',
                'values' => $financials->map(function ($financial) {
                    return price($financial->office_expenses);
                }),
            ],
            'office_expenses_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->office_expenses);
                }),
            ],
            'office_expenses_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('office_expenses');
                }),
            ],
            'depreciation_2' => [
                'label' => 'Depreciation',
                'values' => $financials->map(function ($financial) {
                    return price($financial->depreciation);
                }),
            ],
            'depreciation_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->depreciation);
                }),
            ],
            'depreciation_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('depreciation');
                }),
            ],
            'amortization_2' => [
                'label' => 'Amortization',
                'values' => $financials->map(function ($financial) {
                    return price($financial->amortization);
                }),
            ],
            'amortization_of_revenue' => [
                'label' => '% of Revenue',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentRevenue($financial->amortization);
                }),
            ],
            'amortization_of_growth' => [
                'label' => '% of Growth',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentGrowth('amortization');
                }),
            ],
            'taxes' => [
                'label' => 'Income Taxes',
                'values' => $financials->map(function ($financial) {
                    return price($financial->business_taxes);
                }),
            ],
            'business_taxes_of_pre_tax_income' => [
                'label' => '% pre-tax income',
                'values' => $financials->map(function ($financial) {
                    return $financial->getPercentPreTaxIncome($financial->business_taxes);
                }),
            ],
            'summary_of_non_recurring_expense_adjustments' => [
                'label' => 'Summary of Non-Recurring Expense Adjustments',
                'values' => $financials->map(function ($financial) {
                    return $this->formatFinancialYearHeader($financial);
                }),
            ],
            'expenses' => $expenses->filter->hasInputValues()->map(function ($expense, $key) use ($financials) {
                return [
                    'label' => $expense->name,
                    'values' => $expense->linesInYearRange()->map(function ($line) {
                        return price($line->amount);
                    }),
                ];
            }),
            'total_expense_adjustments' => [
                'label' => 'Total expense adjustments',
                'values' => $expenseTotals->map(function ($total) {
                    return price($total);
                }),
            ],
        ]);
    }

    protected function setRevenueRelationshipIfMissing($lines, $revenue)
    {
        return $lines->map(function ($line) use ($revenue) {
            $line->revenue = $line->revenue ?? $revenue;

            return $line;
        });
    }

    /**
     * Exports the adjusted Financials & Trends rows
     *
     * @return void
     */
    public function exportAdjustedFinancialsTrendsCSV()
    {
        $rows = $this->getAdjustedFinancialsTrends();

        // Create a CSV to write to.
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());

        // Add the rows to the CSV.
        $rows->each(function ($row, $key) use ($csv) {
            // Default
            if ($key !== 'revenues' and $key !== 'expenses') {
                $csv->insertOne($this->mergeRowLabelValues($row));

                return;
            }

            // Expenses
            if ($key === 'expenses') {
                $row->each(function ($expense) use ($csv) {
                    $csv->insertOne($this->mergeRowLabelValues($expense));
                });

                return;
            }

            // Revenue
            if ($key === 'revenues') {
                $row->each(function ($revenueRows) use ($csv) {
                    collect($revenueRows)->each(function ($revenueRow) use ($csv) {
                        $csv->insertOne($this->mergeRowLabelValues($revenueRow));
                    });
                });

                return;
            }
        });

        $csv->output($this->fileName('adjusted-financials-trends'));
    }

    /**
     * Merges the rows label/values together
     *
     * @param \Illuminate\Support\Collection $row
     * @return array
     */
    protected function mergeRowLabelValues($row)
    {
        return array_merge(
            [$row['label']],
            $row['values']->flatten()->toArray()
        );
    }

    /**
     * The exported file name.
     *
     * @param string $suffix
     * @return void
     */
    protected function fileName($suffix)
    {
        $title = kebab_case($this->space->title);
        $suffix = kebab_case($suffix);
        return "{$title}-{$suffix}.csv";
    }
}
