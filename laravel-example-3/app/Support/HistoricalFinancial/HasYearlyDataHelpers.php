<?php

namespace App\Support\HistoricalFinancial;

use Illuminate\Support\Carbon;

trait HasYearlyDataHelpers
{
    /**
     * Get the year range to save.
     *
     *  @param int $yearStart
     *
     * @return Illuminate\Support\Collection
     */
    public function yearRange($yearStart = null)
    {
        if (is_null($yearStart)) {
            $yearStart = isset($this->yearStart) ? $this->yearStart : date('Y');
        }

        return collect([
            'year1' => intval($this->createYear($yearStart)->subYears(2)->format('Y')),
            'year2' => intval($this->createYear($yearStart)->subYears(1)->format('Y')),
            'year3' => intval($this->createYear($yearStart)->format('Y')),
            'year4' => intval($this->createYear($yearStart)->addYear()->format('Y')),
        ]);
    }

    /**
     * Get the year range to save for a select.
     *
     * @return Illuminate\Support\Collection
     */
    public function yearRangeForSelect()
    {
        return collect([
            intval($this->createYear()->subYears(1)->format('Y')),
            intval($this->createYear()->subYears(2)->format('Y')),
            intval($this->createYear()->subYears(3)->format('Y')),
            intval($this->createYear()->subYears(4)->format('Y')),
            optional($this->listing->hf_most_recent_year)->format('Y')
        ])->filter()->unique()->sort()->reverse()->values()
        ->map(function ($year) {
            return [
                'label' => $year,
                'value' => $year,
            ];
        });
    }

    /**
     * Get a carbon year.
     *
     * @param int $year
     * @return Carbon
     */
    public function createYear($year = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        return Carbon::createFromDate($year, 1, 1, null);
    }
}
