<?php

namespace App\Support\HistoricalFinancial;

use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class GeneralDataFormSections
{
    use HasYearlyDataHelpers;

    protected $financials;
    protected $allFinancials;
    protected $yearRange;
    protected $yearStart;

    public function __construct($listing)
    {
        $this->yearStart = $listing->hfYearStart();
        $this->yearRange = $this->yearRange();

        $this->allFinancials = $listing->historicalFinancials;
        $this->financials = $listing->historicalFinancials->filter(function ($financial) use ($listing) {
            return $this->yearRange->contains($financial->year->format('Y'));
        })->sortBy(function ($financial) {
            return intval($financial->year->format('Y'));
        })->keyBy(function ($financial, $key) {
            return $this->yearRange->flip()->get($financial->year->format('Y'));
        });
    }

    public function employeeRelatedExpenses()
    {
        return $this->addValues([
            [
                'name' => 'employee_wages_benefits',
                'label' => 'Employee Wages & Benefits',
                'tooltip' => 'Includes any salary, bonus, stock-based compensation, benefits contributions and payroll taxes',
            ],

            [
                'name' => 'employee_education_training',
                'label' => 'Employee Education & Training Expenses',
                'tooltip' => 'Includes any costs incurred as part of new employee training and any ongoing education and training related expenses',
            ],

            [
                'name' => 'contractor_expenses',
                'label' => 'Contractor Related Expenses',
                'tooltip' => 'Includes any expenses related to contractors or other third-party, part-time workers hired by the company, if applicable',
            ],
        ]);
    }

    public function officeRelatedExpenses()
    {
        return $this->addValues([
            [
                'name' => 'utilities',
                'label' => 'Utilities',
                'tooltip' => 'Includes costs for phone, internet, electricity, water and other similar expenses',
            ],

            [
                'name' => 'rent_lease_expenses',
                'label' => 'Rent or Lease Expenses',
                'tooltip' => 'Includes rent, insurance and other expenses related to the company\'s physical location(s)',
            ],

            [
                'name' => 'office_supplies',
                'label' => 'Office Expenses',
                'tooltip' => 'Includes expenses for office supplies including paper, pens, ink, mailing supplies and other similar items',
            ],
        ]);
    }

    public function sellingGeneralAndAdministrativeExpenses()
    {
        return $this->addValues([
            [
                'name' => 'cost_goods_sold',
                'label' => 'Cost of Goods Sold',
                'tooltip' => 'Includes the total cost of materials and labor to produce or acquire any goods sold. A simple way to calculate COGS is to use the change in inventory over a given period. Start with beginning inventory, add any inventory purchases and then subtract ending inventory to arrive at COGS',
            ],

            [
                'name' => 'transportation',
                'label' => 'Transportation',
                'tooltip' => 'Includes any car, truck or other transportation related expenses for company owned vehicles used for transportation, deliveries and/or distribution of products and services',
            ],

            [
                'name' => 'meals_entertainment',
                'label' => 'Meals & Entertainment',
                'tooltip' => 'Includes expenses related to the sale and marketing of the company\'s products and services to customers and potential customers',
            ],

            [
                'name' => 'travel_expenses',
                'label' => 'Travel Expenses',
                'tooltip' => 'Includes any business travel related expenses including travel for sale and marketing of the company\'s products and services',
            ],

            [
                'name' => 'professional_services',
                'label' => 'Professional Services',
                'tooltip' => 'Includes any legal, accounting, tax preparation or other similar expenses',
            ],
        ]);
    }

    public function financeRelatedExpenses()
    {
        return $this->addValues([
            [
                'name' => 'interest_expense',
                'label' => 'Interest Expense',
                'tooltip' => 'Includes any interest or other expenses related to debt liabilities of the company',
            ],

            [
                'name' => 'depreciation',
                'label' => 'Depreciation',
                'tooltip' => 'Includes non-cash expenses related to physical assets owned by the business that are being depreciated over their useful life',
            ],

            [
                'name' => 'amortization',
                'label' => 'Amortization',
                'tooltip' => 'Includes non-cash expenses related to acquired intangibles such as intellectual property, trademarks and domain names, customer lists, or long-term contracts and agreements that are being amortized over their useful life',
            ],

            [
                'name' => 'general_operational_expenses',
                'label' => 'Other General Operating Expenses',
                'tooltip' => 'Include any other general operating expenses for your business not included in other expense categories',
            ],

            [
                'name' => 'business_taxes',
                'label' => 'Income Taxes',
                'tooltip' => 'Include only if the company pays taxes at the corporate level (as opposed to solely passing through income to owner or owners)',
            ],
        ]);
    }

    public function otherCashFlowItems()
    {
        return $this->addValues([
            [
                'name' => 'capital_expenditures',
                'label' => 'Capital Expenditures',
                'tooltip' => 'Includes the cost to acquire property, equipment, furniture, computers and software and any other capitalized assets',
            ],

            [
                'name' => 'stock_based_compensation',
                'label' => 'Stock-based Compensation',
                'tooltip' => 'Includes any compensation to employees in form of company stock, which is excluded from Earnings Before Interest, Tax, Depreciation, and Amortization (EBITDA) when calculating discretionary cash flow',
            ],

            [
                'name' => 'change_working_capital',
                'label' => 'Decrease / (Increase) in Working Capital',
                'enableNegative' => true,
                'tooltip' => 'An increase in operating working capital from the previous year should be reflected here as a negative number (use of cash) and a decrease should be reflected here as a positive number (source of cash).  A change in working capital is defined as the previous year\'s operating working capital minus the current year\'s operating working capital. Operating working capital is calculated as total current assets, excluding cash, minus total current liabilities, excluding current portion of long-term debt.',
            ],
        ]);
    }

    public function balanceSheetRecurringAssets()
    {
        return $this->addValues([
            [
                'name' => 'cash_equivalents',
                'label' => 'Cash & Cash Equivalents',
                'tooltip' => 'Includes cash as well as cash equivalents such as money-market accounts, U.S. Government Treasury Bills or short-term bonds, or bank certificates of deposit (CDs)',
            ],

            [
                'name' => 'investments',
                'label' => 'Investments',
                'tooltip' => 'Includes any type of investment security such as stocks, ETFs or other equity investments, corporate bonds, mutual funds, or longer-term U.S. Government bonds',
            ],

            [
                'name' => 'accounts_receivable',
                'label' => 'Accounts Receivable',
                'tooltip' => 'Includes any debts or payments owed to the company such as outstanding bills or invoices',
            ],

            [
                'name' => 'inventory',
                'label' => 'Inventory',
                'tooltip' => 'Includes the total cost of materials, in-process products and finished products available for sale',
            ],

            [
                'name' => 'prepaid_expenses',
                'label' => 'Prepaid Expenses',
                'tooltip' => 'Includes any payments for goods or services that have been paid in advance and are amortized
                            over the period the good or service is provided. Prepaid expenses can include payments for
                            things like insurance, contractors, rent, or subscriptions',
            ],

            [
                'name' => 'other_current_assets',
                'label' => 'Other Current Assets',
                'tooltip' => 'Includes any other assets that could be readily converted to cash',
            ],
        ]);
    }

    public function balanceSheetLongTermAssets()
    {
        return $this->addValues([
            [
                'name' => 'property_plant_equipment',
                'label' => 'Property, Plant & Equipment',
                'tooltip' => 'Includes any capitalized assets of the company such as property, equipment or other physical assets that are being depreciated',
            ],

            [
                'name' => 'goodwill',
                'label' => 'Goodwill',
                'tooltip' => 'Intangible asset created through a premium value paid for tangible assets of an acquired company. If your business hasn\'t acquired another business, this will be $0',
            ],

            [
                'name' => 'intangible_assets',
                'label' => 'Other Intangible Assets',
                'tooltip' => 'Includes any additional intangible assets acquired through an acquisition that are being amortized',
            ],

            [
                'name' => 'other_assets',
                'label' => 'Other Assets',
                'tooltip' => 'Includes any other long-term assets owned by the company not included elsewhere',
            ],
        ]);
    }

    public function balanceSheetCurrentLiabilities()
    {
        return $this->addValues([
            [
                'name' => 'accounts_payable',
                'label' => 'Accounts Payable',
                'tooltip' => 'Includes any short term liabilities to suppliers or other creditors',
            ],

            [
                'name' => 'current_debt',
                'label' => 'Short Term Debt & Current Debt',
                'tooltip' => 'Includes any short term or current debt obligations due (debt due within one year)',
            ],

            [
                'name' => 'accrued_liabilities',
                'label' => 'Accrued Liabilities',
                'tooltip' => 'Include expenses that have been incurred by the company but not yet paid.
                            Common liabilities that can be accrued but not paid by period end include: employee compensation,
                            benefits and payroll taxes, rent, interest on outstanding debt obligations, utilities,
                            and property and other taxes',
            ],

            [
                'name' => 'unearned_revenues',
                'label' => 'Unearned Revenues',
                'tooltip' => 'Includes any advanced payments received by the company for goods or services that have not yet been delivered or provided to the customer',
            ],

            [
                'name' => 'other_current_liabilities',
                'label' => 'Other Current Liabilities',
                'tooltip' => 'Includes any other liabilities of the company due within one year that were not included previously',
            ],
        ]);
    }

    public function balanceSheetLongTermLiabilities()
    {
        return $this->addValues([
            [
                'name' => 'long_term_debt',
                'label' => 'Long Term Debt, Less Current Portion',
                'tooltip' => 'Includes any outstanding long-term debt or other similar obligations of the company;
                make sure to exclude any short-term debt that was reflected above so as to not count it twice',
            ],

            [
                'name' => 'deferred_income_taxes',
                'label' => 'Deferred Income Taxes',
                'tooltip' => 'Includes any prior taxes that were deferred and are recorded as an outstanding future liability',
            ],

            [
                'name' => 'deferred_rent_expense',
                'label' => 'Deferred Rent or Lease Expense',
                'tooltip' => 'Includes any deferred rent or lease payments that are recorded as an outstanding future liability',
            ],

            [
                'name' => 'other_liabilities',
                'label' => 'Other Liabilities',
                'tooltip' => 'Includes any other liabilities not included elsewhere',
            ],
        ]);
    }

    public function balanceSheetShareholdersEquity()
    {
        return $this->addValues([
            [
                'name' => 'paid_in_capital',
                'label' => 'Paid In Capital',
                'tooltip' => 'Includes the total amount of contributed capital provided to the company through sales of common and preferred equity, net of any stock repurchases',
            ],

            [
                'name' => 'retained_earnings',
                'label' => 'Retained Earnings',
                'tooltip' => 'Includes the company\'s cumulative retained earnings at the end of each period.
                        This is the net earnings of the company over time less any dividends or other distributions paid out to shareholders',
            ],

            [
                'name' => 'other_equity_accounts',
                'label' => 'Other Equity Accounts',
                'tooltip' => 'Includes any other equity accounts such as unrealized gains/losses on investments,
                        foreign currency translation gains/losses, and gains/losses on company pension plans. Often $0 for most small businesses',
            ],
        ]);
    }

    protected function addValues($rows)
    {
        return collect($rows)->map(function ($row) {
            $attribute = $row['name'];

            $row['values'] = [
                'year1' => optional($this->financials->get('year1'))->$attribute,
                'year2' => optional($this->financials->get('year2'))->$attribute,
                'year3' => optional($this->financials->get('year3'))->$attribute,
                'year4' => optional($this->financials->get('year4'))->$attribute,
                'all' => $this->allFinancials->keyBy(function ($financial) {
                    return $financial->year->format('Y');
                })->map->$attribute,
            ];

            return $row;
        })->toArray();
    }
}
