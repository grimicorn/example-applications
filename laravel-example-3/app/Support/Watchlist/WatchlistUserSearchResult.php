<?php

namespace App\Support\Watchlist;

use App\SavedSearch;
use Illuminate\Support\Collection;

class WatchlistUserSearchResult
{
    protected $search;
    protected $listings;

    public function __construct(SavedSearch $search, Collection $listings)
    {
        $this->search = $search;
        $this->listings = $listings;
    }

    public function search()
    {
        return $this->search;
    }

    public function listings()
    {
        return $this->listings;
    }
}
