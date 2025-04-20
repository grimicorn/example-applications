<?php
namespace Tests\Support;

use Illuminate\Support\Carbon;

trait HasFinancialTestDataHelpers
{
    public $years;

    /**
     * Sets the years value.
     *
     * @param integer $yearStart
     * @return void
     */
    protected function setYears($yearStart = null)
    {
        $yearStart = is_null($yearStart) ? Carbon::now()->subYears(1)->format('Y') : $yearStart;

        $year1 = Carbon::createFromDate($yearStart, 1, 1, null)->subYears(2);
        $year2 = Carbon::createFromDate($yearStart, 1, 1, null)->subYears(1);
        $year3 = Carbon::createFromDate($yearStart, 1, 1, null);
        $year4 = Carbon::createFromDate($yearStart, 1, 1, null)->addYear();

        $this->years = collect([
            intval($year1->format('Y')),
            intval($year2->format('Y')),
            intval($year3->format('Y')),
            intval($year4->format('Y')),
        ]);
    }

    /**
     * Key multiple attributes by their years.
     *
     * @param array $attributes
     * @return array
     */
    protected function keyAttributesByYear($attributes)
    {
        return collect($attributes)
        ->map(function ($attribute) {
            return $this->keyAttributeByYear($attribute);
        })->toArray();
    }

    /**
     * Key an attribute by it's year.
     *
     * @param array $attribute
     * @return array
     */
    protected function keyAttributeByYear($attribute)
    {
        return collect($attribute)
        ->keyBy(function ($item, $key) {
            return $this->years[ $key ];
        })->toArray();
    }

    /**
     * Gets the total for a set of totals by year.
     *
     * @param array $totals
     * @param int $year
     * @return void
     */
    protected function getTotalByYear($totals, $year)
    {
        return $this->getAttributesByYear([
            'total' => $totals,
        ], $year)['total'];
    }

    /**
     * Gets attributes by a specfied year.
     *
     * @param array $attributes
     * @param int $year
     * @param App\Listing $listing
     * @return array
     */
    protected function getAttributesByYear($attributes, $year, $listing = null)
    {
        // Get the attributes by their year.
        $attributes = collect($this->keyAttributesByYear($attributes))
        ->map(function ($attribute) use ($year) {
            return $attribute[ $year ];
        })->toArray();

        // Add year and listing.
        $attributes['year'] = intval($year);
        $attributes['listing_id'] = optional($listing)->id;

        return $attributes;
    }
}
