<?php

namespace App\Support\ListingCompletionScore;

use Illuminate\Support\Carbon;
use App\Support\ListingCompletionScore\Calculations;
use App\Support\ListingCompletionScore\AppliesPenalty;
use App\Support\HistoricalFinancial\RevenueFormSections;
use App\Support\HistoricalFinancial\ExpenseFormSections;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;
use App\Support\HistoricalFinancial\GeneralDataFormSections;

class HistoricalFinancialCalculations extends Calculations
{
    use HasYearlyDataHelpers;
    use AppliesPenalty;

    protected $calculationType = 'financials';
    protected $yearStart;

    public function __construct($listing)
    {
        $this->yearStart = $listing->hfYearStart();

        parent::__construct($listing);
    }

    /**
     * Get the financials.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFinancials()
    {
        return $this->listing->historicalFinancials->filter(function ($financial) {
            return $this->yearRange()->contains(optional($financial->year)->format('Y'));
        });
    }

    /**
     * {@inheritDoc}
     */
    protected function points()
    {
        return collect([
            'sources_of_income' => $this->adjustSectionPoints(1),
            'employee_related_expenses' => $this->adjustSectionPoints(1),
            'office_related_expenses' => $this->adjustSectionPoints(1),
            'selling_general_and_administrative_expenses' => $this->adjustSectionPoints(1),
            'finance_related_expenses' => $this->adjustSectionPoints(1),
            'other_cash_flow_items' => $this->adjustSectionPoints(1),
            'non_recurring_personal_or_extra_expenses' => $this->adjustSectionPoints(1),
            'balance_sheet_recurring_assets' => $this->adjustSectionPoints(1),
            'balance_sheet_long_term_assets' => $this->adjustSectionPoints(1),
            'balance_sheet_current_liabilities' => $this->adjustSectionPoints(1),
            'balance_sheet_long_term_liabilities' => $this->adjustSectionPoints(1),
            'balance_sheet_shareholders_equity' => $this->adjustSectionPoints(1),
        ]);
    }

    /**
     * Section points should not be adjusted based on disabled columns, but this errant function was referenced elsewhere.
     * Therefore the function was rewritten to not subtract anything from $points, which in turn corrected the problem.
     *
     * @param int $points
     * @return void
     */
    protected function adjustSectionPoints($points)
    {
        $deductionPercentage = $this->rowDisabledInputCount() / $this->rowInputCount();
        $deduction = $points * $deductionPercentage;
        $points = $points - 0;

        return $points < 0 ? 0 : $points;
    }

    /**
     * Calculate sources of income.
     *
     * @return integer
     */
    public function sourcesOfIncome()
    {
        return $this->calculateRepeaterDataSectionTotal('revenues');
    }

    /**
     * Calculate employee related expenses.
     *
     * @return integer
     */
    public function employeeRelatedExpenses()
    {
        return $this->calculateGeneralDataSectionTotal('employee_related_expenses');
    }

    /**
     * Calculate office related expenses.
     *
     * @return integer
     */
    public function officeRelatedExpenses()
    {
        return $this->calculateGeneralDataSectionTotal('office_related_expenses');
    }

    /**
     * Calculate selling general and administrative expenses.
     *
     * @return integer
     */
    public function sellingGeneralAndAdministrativeExpenses()
    {
        return $this->calculateGeneralDataSectionTotal('selling_general_and_administrative_expenses');
    }

    /**
     * Calculate finance related expenses.
     *
     * @return integer
     */
    public function financeRelatedExpenses()
    {
        return $this->calculateGeneralDataSectionTotal('finance_related_expenses');
    }

    /**
     * Calculate other cash flow items.
     *
     * @return integer
     */
    public function otherCashFlowItems()
    {
        return $this->calculateGeneralDataSectionTotal('other_cash_flow_items');
    }

    /**
     * Calculate non recurring personal or extra expenses.
     *
     * @return integer
     */
    public function nonRecurringPersonalOrExtraExpenses()
    {
        return $this->calculateRepeaterDataSectionTotal('expenses');
    }

    /**
     * Calculate balance sheet recurring assets.
     *
     * @return integer
     */
    public function balanceSheetRecurringAssets()
    {
        return $this->calculateGeneralDataSectionTotal('balance_sheet_recurring_assets');
    }

    /**
     * Calculate balance sheet long term assets.
     *
     * @return integer
     */
    public function balanceSheetLongTermAssets()
    {
        return $this->calculateGeneralDataSectionTotal('balance_sheet_long_term_assets');
    }

    /**
     * Calculate balance sheet current liabilities.
     *
     * @return integer
     */
    public function balanceSheetCurrentLiabilities()
    {
        return $this->calculateGeneralDataSectionTotal('balance_sheet_current_liabilities');
    }

    /**
     * Calculate balance sheet long term liabilities.
     *
     * @return integer
     */
    public function balanceSheetLongTermLiabilities()
    {
        return $this->calculateGeneralDataSectionTotal('balance_sheet_long_term_liabilities');
    }

    /**
     * Calculate balance sheet shareholders equity.
     *
     * @return integer
     */
    public function balanceSheetShareholdersEquity()
    {
        return $this->calculateGeneralDataSectionTotal('balance_sheet_shareholders_equity');
    }

    /**
     * {@inheritDoc}
     */
    public function total()
    {
        return array_sum([
            $this->sourcesOfIncome(),
            $this->employeeRelatedExpenses(),
            $this->officeRelatedExpenses(),
            $this->sellingGeneralAndAdministrativeExpenses(),
            $this->financeRelatedExpenses(),
            $this->otherCashFlowItems(),
            $this->nonRecurringPersonalOrExtraExpenses(),
            $this->balanceSheetRecurringAssets(),
            $this->balanceSheetLongTermAssets(),
            $this->balanceSheetCurrentLiabilities(),
            $this->balanceSheetLongTermLiabilities(),
            $this->balanceSheetShareholdersEquity(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function totalPossible()
    {
        return collect($this->points())->sum();
    }

    /**
     * {@inheritDoc}
     */
    public function sectionTotalPossible($section)
    {
        return floatval(collect($this->points())->get($section));
    }

    /**
     * {@inheritDoc}
     */
    public function sectionTotal($section)
    {
        // We need to find a matching method.
        // Methods should be section keys that are snake case converted to camel case.
        // Example: sources_of_income -> sourcesOfIncome
        $method = camel_case($section);
        if (!method_exists($this, $method)) {
            return 0;
        }

        return floatval($this->$method());
    }

    /**
     * Checks if points should be applied due to date rules by key.
     *
     * @param int $year
     * @return void
     */

    protected function disabledByDateRuleByKey($yearKey)
    {
        $year = $this->yearRange()->get($yearKey);

        return $this->disabledByDateRule($year);
    }

    /**
     * Checks if points should be applied due to date rules.
     *
     * @param int $year
     * @return void
     */
    protected function disabledByDateRule($year)
    {
        // If the business is less then the required financial span then they
        // shouldn't be penalized for not being founded long enough ago.
        if ($this->priorToYearEstablished($year)) {
            return true;
        }

        // If it is early in the year and business may not have financial data yet.
        if ($this->isCurrentYearNoneQuarter($year)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the date is prior to the year established by the year.
     *
     * @param int $year
     * @return void
     */
    protected function priorToYearEstablished($year)
    {
        // If for some reason the year established is empty then it
        // is not possible for the year to be prior to year established.
        if (is_null($this->listing->year_established)) {
            return false;
        }

        $established = intval($this->listing->year_established);
        $year = intval($year);

        return ($year < $established);
    }

    /**
     * Checks if the current year is the none quarter
     *
     * @param int $year
     * @return boolean
     */
    protected function isCurrentYearNoneQuarter($year)
    {
        $year = intval($year);

        // If they have not entered something then that is on them.
        if (is_null($this->listing->hf_most_recent_quarter)) {
            return false;
        }

        $most_recent_year = optional($this->listing->hf_most_recent_year);
        $start_year = optional($most_recent_year->addYear())->format('Y');

        // This should only be valid for the most recent year year.
        if ($year !== intval($start_year)) {
            return false;
        }

        // If the most recent quarter is not none then we are done.
        if (intval($this->listing->hf_most_recent_quarter) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Calculates general data section total.
     *
     * @param string $section
     * @return void
     */
    protected function calculateGeneralDataSectionTotal($section)
    {
        $fieldsPerRow = $this->rowEnabledInputCount();
        $possible = $this->sectionTotalPossible($section);
        $sectionFields = $this->sectionFields($section);
        $sectionCounts = $sectionFields->pluck('values')->map->forget('all')->map->reject(function ($value, $key) {
            return !$this->checkValue($value, $key);
        })->map->count();

        $sectionFieldsCount = $sectionFields->count() * $fieldsPerRow;

        if ($sectionCounts->sum() === 0) {
            return 0;
        }

        $total = ($sectionCounts->sum() / $sectionFieldsCount) * $possible;

        return $this->calculatePenalty($total, $this->listing, $this->calculationType);
    }

    /**
     * Get the row enabled input count
     *
     * @return int
     */
    protected function rowEnabledInputCount()
    {
        $count = $this->rowInputCount() - $this->rowDisabledInputCount();

        return $count < 0 ? 0 : $count;
    }

    /**
     * Get the row disabled input count
     *
     * @return int
     */
    protected function rowDisabledInputCount()
    {
        // Handle prior year inputs
        $disabledCount = $this->priorToYearEstablishedFields()->count();

        // Handle none available
        $quarter = $this->listing->hf_most_recent_quarter;
        if (!is_null($quarter) and intval($quarter) === 0) {
            $disabledCount =  $disabledCount + 1;
        }

        return $disabledCount;
    }

    /**
     * Get the row input count
     *
     * @return int
     */
    protected function rowInputCount()
    {
        return 4;
    }

    /**
     * Gets the years prior to the established date.
     *
     * @return int
     */
    protected function priorToYearEstablishedFields()
    {
        return $this->yearRange()->filter(function ($year) {
            return $this->priorToYearEstablished($year);
        });
    }

    /**
     * Gets the years during and after to the established date.
     *
     * @return int
     */
    protected function currentToYearEstablishedFields()
    {
        return $this->yearRange()->reject(function ($year) {
            return $this->priorToYearEstablished($year);
        });
    }

    /**
     * Calculates repeater data section total.
     *
     * @param string $relationshipAttribute
     * @return void
     */
    protected function calculateRepeaterDataSectionTotal($relationshipAttribute)
    {
        $section = $this->getRepeaterSectionByRelationshipAttribute($relationshipAttribute);
        $score = 0;
        $inputs = $this->rowEnabledInputCount();
        $points = $this->sectionTotalPossible($section);
        $rows = $this->listing->$relationshipAttribute;

        if (!$rows) {
            return $score;
        }

        // Lets get the counts for each row
        $count = $rows->map(function ($row) {
            // Check the amounts
            $count = $row->lines->map(function ($line) {
                // Make sure that the line is within the year range.
                if (!$this->yearRange()->contains(optional($line->year)->format('Y'))) {
                    return null;
                }

                // Check if the value is valid based on pre-set date rules.
                if ($this->disabledByDateRule(optional($line->year)->format('Y'))) {
                    return null; // The overall possible inputs will be reduced
                }

                return $line->amount;
            })->filter(function ($value) {
                return $value !== false and !is_null($value);
            })->count();

            return $count;
        })->sum();

        // If the count is zero or less then the score is zero
        if ($count <= 0) {
            return $score;
        }

        // Now we will need to see what percentage has been filled out range will be 0-1
        $percentage = $count / ($rows->count() * $inputs);

        // Now we take the percentage of inputs filled out
        // and apply that to the points.
        // Examples (note: numbers not updated to reflect new point values):
        // 1 rows with 1 amount should be  1.25
        // 5 × (1 / (1 × 4))
        //
        // 2 rows with 2 amounts should be  1.25
        // 5 × (2 / (2 × 4))
        //
        // 3 rows with 3 amounts should be 1.25
        // 5 × (3 / (3 × 4))
        $total = $points * $percentage;

        return $this->calculatePenalty($total, $this->listing, $this->calculationType);
    }

    /**
     * Get the repeaters section by the relationship attribute
     *
     * @param string $relationship
     * @return void
     */
    protected function getRepeaterSectionByRelationshipAttribute($attribute)
    {
        switch ($attribute) {
            case 'revenues':
                return 'sources_of_income';
                break;

            case 'expenses':
                return 'non_recurring_personal_or_extra_expenses';
                break;
        }
    }

    /**
     * Check if the value is valid.
     *
     * @param mixed $value
     * @param string $yearKey
     * @return void
     */
    protected function checkValue($value, $yearKey)
    {
        // Short circuit if the year is prior to the year established
        if ($this->disabledByDateRuleByKey($yearKey)) {
            return false; // The overall total possible will be reduced by however many years that are disabled
        }

        return !is_null($value) and '' !== $value;
    }

    /**
     * Get a section fields.
     *
     * @param string $section
     * @return void
     */
    protected function sectionFields($section)
    {
        return $this->allSectionFields()->get($section, []);
    }

    /**
     * Get all section fields.
     *
     * @return void
     */
    protected function allSectionFields()
    {
        $generalData = new GeneralDataFormSections($this->listing);
        return r_collect([
            'sources_of_income' => (new RevenueFormSections($this->listing))->section(),
            'non_recurring_personal_or_extra_expenses' => (new ExpenseFormSections($this->listing))->section(),
            'employee_related_expenses' => $generalData->employeeRelatedExpenses(),
            'office_related_expenses' => $generalData->officeRelatedExpenses(),
            'selling_general_and_administrative_expenses' => $generalData->sellingGeneralAndAdministrativeExpenses(),
            'finance_related_expenses' => $generalData->financeRelatedExpenses(),
            'other_cash_flow_items' => $generalData->otherCashFlowItems(),
            'balance_sheet_recurring_assets' => $generalData->balanceSheetRecurringAssets(),
            'balance_sheet_long_term_assets' => $generalData->balanceSheetLongTermAssets(),
            'balance_sheet_current_liabilities' => $generalData->balanceSheetCurrentLiabilities(),
            'balance_sheet_long_term_liabilities' => $generalData->balanceSheetLongTermLiabilities(),
            'balance_sheet_shareholders_equity' => $generalData->balanceSheetShareholdersEquity(),
        ]);
    }

    /**
     * Calculates the listing completion score points per input.
     *
     * @param $inputs
     * @param $total
     * @param $points
     * @return int
     */
    protected function perInputPoints($total, $points)
    {
        return $this->perInputPercentage($total) * $points;
    }

    /**
     * Calculates the listing completion score percentage per input.
     *
     * @param $total
     * @return int
     */
    protected function perInputPercentage($total)
    {
        return 1 / $total;
    }

    /**
     * Count the non empty attribute count.
     *
     * @param string $attribute
     * @return void
     */
    protected function attributeCount($attribute)
    {
        return $this->getFinancials()->pluck($attribute)->filter()->count();
    }
}
