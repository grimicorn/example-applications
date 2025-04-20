<?php

namespace App\Support\HistoricalFinancial;

use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class RowFormSections
{
    use HasYearlyDataHelpers;

    protected $models;
    protected $yearRange;
    protected $yearStart;

    public function __construct($listing, $models)
    {
        $this->yearStart = $listing->hfYearStart();
        $this->yearRange = $this->yearRange();
        $this->models = $models;
    }

    public function section()
    {
        return $this->models->map(function ($model) {
            $values = $this->getValues($model);
            $values['customName'] = $model->name;

            return $values;
        });
    }

    protected function getValues($model)
    {
        $values = $model->lines->filter(function ($line) {
            return $this->yearRange->contains($line->year->format('Y'));
        })
        ->keyBy(function ($line, $key) {
            return $this->yearRange->flip()->get($line->year->format('Y'));
        })
        ->map(function ($line, $key) {
            return $line->amount;
        });

        // Get all of the current values if needed.
        $values->put('all', $model->lines->keyBy(function ($line) {
            return $line->year->format('Y');
        })->map->amount);

        return $values->toArray();
    }
}
