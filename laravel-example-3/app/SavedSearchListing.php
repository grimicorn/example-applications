<?php

namespace App;

use App\Listing;
use App\SavedSearch;
use Illuminate\Database\Eloquent\Model;

class SavedSearchListing extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function listing()
    {
        $this->belongsTo(Listing::class);
    }

    public function savedSearch()
    {
        $this->belongsTo(SavedSearch::class);
    }
}
