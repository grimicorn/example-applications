<?php

namespace App\Support\ListingCompletionScore;

abstract class Calculations
{
    protected $listing;
    public $total_percentage;

    public function __construct($listing)
    {
        $this->listing = $listing;
        $this->total_percentage = $this->totalPercentage();
    }

    /**
     * Get the possible point values.
     *
     * @return Illuminate\Support\Collection
     */
    abstract protected function points();

    /**
     * Get the total.
     *
     * @return integer
     */
    abstract public function total();

    /**
     * Get the total of all values.
     *
     * @return integer
     */
    abstract public function totalPossible();

    /**
     * Get the total possible for a section.
     *
     * @param string  $section
     * @return integer
     */
    abstract public function sectionTotalPossible($section);

    /**
     * Get the total for a section.
     *
     * @param string  $section
     * @return integer
     */
    abstract public function sectionTotal($section);

    /**
     * Gets all section percentages.
     *
     * @param boolean $forDisplay
     * @return void
     */
    public function sectionPercentages($forDisplay = false)
    {
        return $this->points()->keys()->flip()->map(function ($item, $key) use ($forDisplay) {
            if ($forDisplay) {
                return $this->sectionPercentageForDisplay($key);
            }

            return $this->sectionPercentage($key);
        });
    }

    /**
     * Get the section percentage.
     *
     * @param string $section
     * @return float
     */
    public function sectionPercentage($section)
    {
        $possible = $this->sectionTotalPossible($section);
        $total = $this->sectionTotal($section);

        if ($possible <= 0) {
            return 0;
        }

        return floatval($total/$possible);
    }

    /**
     * Section percentage for display.
     *
     * @param string $section
     * @return integer
     */
    public function sectionPercentageForDisplay($section)
    {
        $percentage = $this->sectionPercentage($section);

        return intval(round($percentage * 100));
    }

    /**
     * Get the total percentage.
     *
     * @return float
     */
    public function totalPercentage()
    {
        if ($this->totalPossible() <= 0) {
            return 0;
        }

        return floatval($this->total()/$this->totalPossible());
    }

    /**
    * Section percentage for display.
    *
    * @return integer
    */
    public function totalPercentageForDisplay()
    {
        $percentage = $this->totalPercentage();

        return intval(round($percentage * 100));
    }
}
