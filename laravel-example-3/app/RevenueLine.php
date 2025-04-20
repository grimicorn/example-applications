<?php

namespace App;

use App\Revenue;
use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Support\HistoricalFinancial\FormatsPercentages;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class RevenueLine extends BaseModel
{
    use HasYearlyDataHelpers;
    use FormatsPercentages;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'year' => 'date',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['revenue'];

    public function revenue()
    {
        return $this->belongsTo('App\Revenue');
    }

    public function getPreviousYearRevenueLineAttribute()
    {
        $prevYear = $this->year->subYear()->format('Y');

        return $this->revenue->lines->filter(function ($line) use ($prevYear) {
            return intval($line->year->format('Y')) === intval($prevYear);
        })->first();
    }

    public function getIsCurrentYearAttribute()
    {
        return intval($this->year->format('Y')) === intval(date('Y'));
    }

    public function getPercentGrowth()
    {
        if ($this->isFinalYear()) {
            return 'N/A';
        }

        $currentValue = floatval($this->amount);
        $previousValue = floatval(optional($this->previous_year_revenue_line)->amount);

        if (0 >= $previousValue) {
            return 'N/A';
        }

        $value = ($currentValue / $previousValue) - 1;

        return $this->formatPercentage($value * 100);
    }

    public function isFinalYear()
    {
        return $this->revenue->listing->isHfFinalYear(
            optional($this->year)->format('Y')
        );
    }
}
