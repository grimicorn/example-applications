<?php

namespace App\Support\Listing;

trait HasSearchOptions
{
    public function transactionTypes()
    {
        return [
            'Brokered',
            'For Sale by Owner',
        ];
    }

    public function listingUpdatedAt()
    {
        return [
            'Within the last month',
            'Within the last three months',
            'Within the last year',
        ];
    }
}
