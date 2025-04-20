<?php

namespace App\Support\HistoricalFinancial;

use App\Support\HistoricalFinancial\RowFormSections;

class ExpenseFormSections extends RowFormSections
{
    public function __construct($listing)
    {
        parent::__construct($listing, $listing->expenses);
    }
}
