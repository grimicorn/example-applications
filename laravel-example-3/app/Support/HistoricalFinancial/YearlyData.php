<?php

namespace App\Support\HistoricalFinancial;

use App\HistoricalFinancial;
use App\Support\HistoricalFinancial\YearlyExpense;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class YearlyData
{
    use HasYearlyDataHelpers;

    protected $listing;
    protected $fillable;
    protected $request;
    protected $financials;
    protected $save;
    protected $yearStart;

    public function __construct($listing, $save = true)
    {
        $this->save = $save;
        $this->fillable = collect((new HistoricalFinancial)->getFillable());
        $this->request = request();
        $this->setListing($listing);
    }

    /**
     * Save the general data.
     *
     * @return void
     */
    public function saveGeneralData()
    {
        return $this->yearRange()->map(function ($year, $yearKey) {
            // Get the fields.
            $fields = collect($this->request->get($yearKey, []))
            ->filter(function ($item, $key) {
                return $this->fillable->contains($key);
            })->map(function ($amount, $key) {
                $amount = ($amount === '') ? null : $amount;
                return is_null($amount) ? null : intval($amount);
            })->toArray();

            // Save the financials.
            $financial = $this->getFinancial($year);
            $financial->fill($fields);

            if ($this->save) {
                $financial->save();
            }

            return $financial;
        })->values();
    }

    /**
     * Saves the revenue data.
     *
     * @return void
     */
    public function saveRevenueData()
    {
        return (new YearlyRevenue($this->listing, $this->save))->save();
    }

    /**
     * Saves the expense data.
     *
     * @return void
     */
    public function saveExpenseData()
    {
        return (new YearlyExpense($this->listing, $this->save))->save();
    }

    /**
     * Gets a financial record.
     *
     * @param int $year
     * @return HistoricalFinancial
     */
    protected function getFinancial($year)
    {
        $financial = $this->financials->filter(function ($financial) use ($year) {
            return intval($financial->year->format('Y')) === intval($year);
        })->first();
        if (is_null($financial)) {
            $financial = new HistoricalFinancial;
            $financial->year = $this->createYear($year);
            $financial->listing_id = $this->listing->id;
        }

        return $financial;
    }

    /**
     * Set the listing
     *
     * @param App\Listing $listing
     * @return void
     */
    public function setListing($listing)
    {
        $this->yearStart = $listing->hfYearStart();
        $this->listing = $listing;
        $this->financials = $this->listing->historicalFinancials;
    }
}
