<?php

namespace App\Support\HistoricalFinancial;

trait FormatsPercentages
{

    /**
     * Formats the percentage to match required output format.
     *
     * @param float $value
     * @return void
     */
    public function formatPercentage($value)
    {
        $value = floatval($value);

        if ($value < 0) {
            $number = number_format($value * -1, 1);
            return "({$number}%)";
        }

        return number_format($value, 1) . '%';
    }
}
