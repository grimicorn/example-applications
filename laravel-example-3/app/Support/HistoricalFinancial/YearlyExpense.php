<?php

namespace App\Support\HistoricalFinancial;

use App\Support\HistoricalFinancial\YearlyFinancials;

class YearlyExpense extends YearlyFinancials
{
    public function __construct($listing, $save = false)
    {
        parent::__construct($listing, $type = 'expense', $save);
    }
}
