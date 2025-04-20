<?php

namespace App;

use App\BaseModel;
use App\BusinessCategory;
use App\SavedSearchListing;
use App\Support\ChecksIsOwner;
use App\Support\HasSearchInputs;
use Illuminate\Database\Eloquent\Model;
use App\Support\Watchlist\WatchlistMatches;

class SavedSearch extends BaseModel
{
    use ChecksIsOwner;
    use HasSearchInputs;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
            'name',
            'transaction_type',
            'listing_updated_at',
            'business_categories',
            'keyword',
            'zip_code_radius',
            'zip_code',
            'city',
            'state',
            'asking_price_min',
            'asking_price_max',
            'cash_flow_min',
            'cash_flow_max',
            'pre_tax_income_min',
            'pre_tax_income_max',
            'ebitda_min',
            'ebitda_max',
            'revenue_min',
            'revenue_max',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'listing_updated_at' => 'array',
        'business_categories' => 'array',
        'user_id' => 'integer',
        'zip_code_radius' => 'integer',
        'asking_price_min' => 'integer',
        'asking_price_max' => 'integer',
        'cash_flow_min' => 'integer',
        'cash_flow_max' => 'integer',
        'pre_tax_income_min' => 'integer',
        'pre_tax_income_max' => 'integer',
        'ebitda_min' => 'integer',
        'ebitda_max' => 'integer',
        'revenue_min' => 'integer',
        'revenue_max' => 'integer',
    ];

    protected $appends = [
        'zip_code_radius_label',
        'business_category_labels',
        'asking_price_min_max',
        'show_url',
        'match_count',
    ];

    /**
     * Get the user that owns the saved search.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function notifications()
    {
        return $this->hasMany('App\SavedSearchNotification');
    }

    /**
     * Get the saved search business categories.
     *
     * @param  string  $value
     * @return void
     */
    public function getBusinessCategoriesAttribute($value)
    {
        $value = $this->castAttribute('business_categories', $value);

        return collect($value)->map(function ($category) {
            return intval($category);
        })->toArray();
    }

    /**
     * Get the saved search zip code and radius.
     *
     * @param  string  $value
     * @return string
     */
    protected function getZipCodeRadiusLabelAttribute()
    {
        if ($this->zip_code) {
            return "{$this->zip_code_radius} miles";
        }
    }

    /**
     * Get the saved search business category labels.
     *
     * @return string
     */
    public function getBusinessCategoryLabelsAttribute()
    {
        if (!$this->business_categories) {
            return [];
        }

        $categories = BusinessCategory::whereIn(
            'id',
            $this->business_categories
        );

        return $categories->pluck('label');
    }

    /**
     * Get the saved search business category labels.
     *
     * @return string
     */
    public function getAskingPriceMinMaxAttribute()
    {
        $min = $this->asking_price_min;
        $max = $this->asking_price_max;

        $min = $min ? price($min) : 'No Min';
        $max = $max ? price($max) : 'No Max';

        return "{$min} - {$max}";
    }

    /**
     * Get the saved search show url.
     *
     * @return string
     */
    public function getShowUrlAttribute()
    {
        return route('watchlists.show', ['id' => $this->id]);
    }

    public function getMatchCountAttribute()
    {
        return $this->listings->count();
    }

    public function savedSearchListings()
    {
        return $this->hasMany(SavedSearchListing::class);
    }

    public function getListingsAttribute()
    {
        return $this->listings()->get();
    }

    public function listings()
    {
        return Listing::whereIn('id', $this->savedSearchListings->pluck('listing_id'))->published();
    }

    public function getNewListingsAttribute()
    {
        return $this->newListings()->get();
    }

    public function newListings()
    {
        $query = $this->listings();

        if ($this->refreshed_at) {
            $listings = $this->savedSearchListings()->where('created_at', '>=', $this->refreshed_at);
        } else {
            $listings = $this->savedSearchListings;
        }

        return Listing::whereIn('id', $listings->pluck('listing_id'))->published();
    }

    public function hasNewListings()
    {
        return !$this->newListings->isEmpty();
    }

    public function saveByRequest($updateMatches = true)
    {
        // Get the inputs
        $inputs = collect(request()->only($this->getFillable()))->map(function ($field) {
            return ($field === '') ? null : $field;
        });

        if (!request()->has('business_categories')) {
            $this->business_categories = null;
        }

        // Save the search
        $this->user_id = $this->user_id ? : auth()->id();
        $this->fill($inputs->toArray());
        $this->save();
        $this->fresh();

        // Set the search inputs (if needed)
        if (request()->get('flush')) {
            $inputs->put('saved_search_id', $this->id);
            $this->setSearchInputs($inputs, 'listing.search.inputs');
        }

        // Update the matches
        if ($updateMatches) {
            (new WatchlistMatches)->refreshForSearch($this);
        }

        return $this;
    }

    public function sortedMatches($perPage = null)
    {
        $query = $this->listings()->orderByDesc('current_score_total')->published();
        if (!$perPage) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }
}
