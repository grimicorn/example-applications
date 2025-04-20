<?php

namespace App\Support\HistoricalFinancial;

use App\ExchangeSpace;
use App\Support\HistoricalFinancial\TableRows;

class PreviewTableRows extends TableRows
{
    public function __construct($listing)
    {
        $space = new ExchangeSpace;
        $space->listing = $listing;
        parent::__construct($space);
    }
}
