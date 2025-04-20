<?php

namespace App\Support\TableFilters;

use App\Listing;
use App\Favorite;

class FavoriteTableFilter extends TableFilter
{
    /**
     * {@inheritDoc}
     */
    public function paginated($perPage = 10)
    {
        $listings = $this->get()->map(function ($favorite) {
            $favorite->score_total_percentage = $favorite->score_total_percentage;
            $favorite->score_total = $favorite->score_total;

            return $favorite;
        });

        $listings = $this->sort($listings)->map(function ($listing) {
            $listing->cover_photo_favorite_thumbnail_url = $listing->cover_photo_favorite_thumbnail_url;
            return $listing;
        });

        $count = ($listings->count() > 0) ? $listings->count() : 1;

        return $listings->values()->paginate($count);
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        $results = $this->query()->get();

        if (request()->has('keyword')) {
            $results = $results->whereIn(
                'id',
                $this->getFavoriteListingIds()
            );
        }

        return $results;
    }

    protected function sort($listings)
    {
        $sort_key = $this->getSortKey();

        switch ($sort_key) {
            case 'asking_price_low_to_high':
                $listings = $listings->sortBy('asking_price');
                break;

            case 'asking_price_high_to_low':
                $listings = $listings->sortByDesc('asking_price');
                break;

            case 'lcs_high_to_low':
                $listings = $listings->sortByDesc('current_score_total');
                break;
        }

        return $listings->values();
    }

    /**
     * {@inheritDoc}
     */
    protected function query()
    {
        return Listing::whereIn('id', $this->getWhereIds())->published();
    }

    /**
     * Get the where ids.
     */
    protected function getWhereIds()
    {
        $keyword = request()->get('keyword');
        $favorite_ids = $this->getFavoriteListingIds();

        if (!$keyword) {
            return $favorite_ids;
        }

        return collect($this->getScoutIds())->filter(function ($id) use ($favorite_ids) {
            return in_array($id, $favorite_ids);
        })->toArray();
    }

    /**
     * Get the scout ids
     *
     * @return array
     */
    protected function getScoutIds()
    {
        $keyword = request()->get('keyword');
        if (!$keyword) {
            return $keyword;
        }

        $raw = collect(Listing::search($keyword)->raw());
        return collect($raw->get('hits', $raw->get('results', [])))
            ->map->objectID
            ->toArray();
    }

    protected function getFavoriteListingIds()
    {
        return Favorite::ofCurrentUser()->get()
        ->pluck('listing_id')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function getSortKey()
    {
        return $this->request->get('sortKey', 'lcs_high_to_low');
    }
}
