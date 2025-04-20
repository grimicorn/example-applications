<?php

namespace App\Support\TableFilters;

use App\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\TableFilters\TableFilter;

class ListingTableFilter extends TableFilter
{
    /**
     * {@inheritDoc}
     */
    public function paginated($perPage = 10)
    {
        if (!$this->sortingByScoreTotal()) {
            $listings = $this->query()->get();
        } else {
            $listings = $this->query()->get()->sortBy(function ($listing) {
                return $listing->score_total;
            });

            if ($this->getSortOrder() === 'desc') {
                $listings = $listings->reverse();
            }
        }


        return $listings->map(function ($listing) {
            $listing->score_total_percentage_for_display = $listing->score_total_percentage_for_display;

            return $listing;
        })->values()->paginate($perPage);
    }

    /**
     * Checks if sorting by score total.
     *
     * @return void
     */
    protected function sortingByScoreTotal()
    {
        return $this->getSortKey() === 'score_total';
    }

    /**
     * Builds up the filter query.
     *
     * @return Builder
     */
    protected function query()
    {
        $sortOrder = $this->getSortOrder();
        $sortKey = $this->getSortKey();

        // Set the query
        if ($this->sortingByScoreTotal()) {
            $query = Listing::where('user_id', Auth::id());
        } else {
            $query = Listing::orderBy($sortKey, $sortOrder)
            ->where('user_id', Auth::id());
        }

        // If search is not empty lets try to find the value somewhere.
        if ($search = $this->request->get('search')) {
            $query->where('business_name', 'LIKE', "%{$search}%");

            // Since the status will be a boolean lets check for the status if a user searches for publish or draft.
            $contains_published = str_contains('published', strtolower($search));
            $contains_draft = str_contains('draft', strtolower($search));
            if ($contains_published or $contains_draft) {
                $query->orWhere('published', $contains_published);
            }
        }

        return $query;
    }

    /**
     * Gets the sort key.
     *
     * @return string
     */
    public function getSortKey()
    {
        $key = $this->request->get('sortKey', 'created_at');

        return is_null($key) ? 'created_at' : $key;
    }

    /**
     * Gets the sort order.
     *
     * @return string
     */
    protected function getSortOrder()
    {
        $default = ($this->getSortKey() === 'created_at') ? 'desc' : 'asc';
        return $this->request->get('sortOrder', $default);
    }
}
