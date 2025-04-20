<?php

namespace App\Support\HistoricalFinancial;

use App\Support\HistoricalFinancial\RowFormSections;

class RevenueFormSections extends RowFormSections
{
    public function __construct($listing)
    {
        parent::__construct($listing, $listing->revenues);
    }
}
