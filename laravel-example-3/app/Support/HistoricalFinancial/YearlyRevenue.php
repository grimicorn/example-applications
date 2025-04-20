<?php

namespace App\Support\HistoricalFinancial;

use App\Support\HistoricalFinancial\YearlyFinancials;

class YearlyRevenue extends YearlyFinancials
{
    public function __construct($listing, $save = false)
    {
        parent::__construct($listing, $type = 'revenue', $save);
    }
}
