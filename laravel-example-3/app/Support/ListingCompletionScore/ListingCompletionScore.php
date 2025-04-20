<?php

namespace App\Support\ListingCompletionScore;

use App\ListingCompletionScoreTotal;
use app\Support\ListingCompletionScore\StaleDataPenalty;
use App\Support\ListingCompletionScore\ListingCalculations;
use App\Support\ListingCompletionScore\HistoricalFinancialCalculations;

class ListingCompletionScore
{
    protected $listing;
    public $businessOverviewCalculations;
    public $historicalFinancialCalculations;

    public function __construct($listing)
    {
        $this->setListing($listing);
    }

    /**
     * Set the listing
     *
     * @param App\Listing $listing
     * @return void
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
        $this->businessOverviewCalculations = new BusinessOverviewCalculations($listing);
        $this->historicalFinancialCalculations = new HistoricalFinancialCalculations($listing);

        return $this;
    }

    /**
     * Total score
     *
     * @return integer
     */
    public function total()
    {
        return array_sum([
            $this->businessOverviewCalculations->total(),
            $this->historicalFinancialCalculations->total(),
        ]);
    }

    /**
     * Total score
     *
     * @return integer
     */
    public function totalPossible()
    {
        return array_sum([
            $this->businessOverviewCalculations->totalPossible(),
            $this->historicalFinancialCalculations->totalPossible(),
        ]);
    }

    /**
     * Gets the total percentage.
     *
     * @return float
     */
    public function totalPercentage()
    {
        $percentage = floatval($this->total()/$this->totalPossible());

        return $percentage;
    }

    /**
     * Gets the total percentage for display.
     *
     * @return integer
     */
    public function totalPercentageForDisplay()
    {
        $percentage = $this->totalPercentage() * 100;

        return intval(round($percentage));
    }

    /**
     * Gets the show value.
     *
     * @param string $type
     * @param string $section
     * @param string $subsection
     * @return float
     */
    public function getShowValue($type, $section, $subsection = null)
    {
        $section = str_replace('-', '_', snake_case($section));
        $calculations = $this->showValueCalculations($type);
        if (is_null($calculations)) {
            return 0;
        }

        switch ($section) {
            case 'total':
                return $calculations->total();
                break;

            case 'total_percentage':
                return $calculations->totalPercentage();
                break;

            case 'total_possible':
                return $calculations->totalPossible();
                break;

            default:
                return $this->getShowSectionValue(
                    $calculations,
                    $section,
                    $subsection
                );
                break;
        }
    }

    /**
     * Gets the show section value.
     *
     * @param Calculations $calculations
     * @param string $section
     * @param string $subsection
     * @return void
     */
    protected function getShowSectionValue($calculations, $section, $subsection = null)
    {
        switch ($subsection) {
            case 'total':
                return $calculations->sectionTotal($section);
                break;

            case 'total_possible':
                return $calculations->sectionTotalPossible($section);
                break;

            default:
                return $calculations->sectionPercentage($section);
                break;
        }
    }

    /**
     * Get the calculations for the show value.
     *
     * @param string $type
     *
     * @return Calculations
     */
    protected function showValueCalculations($type)
    {
        switch ($type) {
            case 'overview':
                return $this->businessOverviewCalculations;
                break;

            case 'financial':
                return $this->historicalFinancialCalculations;
                break;

            default:
                return null;
                break;
        }
    }

    /**
     * Save the completion score.
     *
     * @return void
     */
    public function save()
    {
        // Set the total
        $total = $this->listing->listingCompletionScoreTotal;
        if (is_null($total)) {
            $total = new ListingCompletionScoreTotal;
            $total->listing_id = $this->listing->id;
        }
        $total->fill($this->saveFields());
        $total->save();
    }

    /**
     * The fields to save.
     *
     * @return array
     */
    protected function saveFields()
    {
        $business = $this->businessOverviewCalculations;
        $financial = $this->historicalFinancialCalculations;
        $fields = array_merge(
            $business->sectionPercentages(true)->toArray(),
            $financial->sectionPercentages(true)->toArray()
        );

        $fields['total'] = $this->totalPercentageForDisplay();
        $fields['historical_financials'] = $financial->totalPercentageForDisplay();
        $fields['business_overview'] = $business->totalPercentageForDisplay();

        return $fields;
    }

    /**
     * The stale data error message
     *
     *  @param boolean $isFinancials
     *
     * @return string
     */
    public function staleDataErrorMessage($isFinancials = false)
    {
        return (new StaleDataPenalty($this->listing))->errorMessage($isFinancials);
    }

    /**
     * Checks if the listing has stale data.
     *
     * @return boolean
     */
    public function hasStaleData()
    {
        return (new StaleDataPenalty($this->listing))->isStale();
    }
}
