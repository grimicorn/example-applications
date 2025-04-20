<?php

namespace App\Support\Listing;

use App\Listing;
use Carbon\Carbon;
use App\Support\BaseSearch;
use App\Support\Listing\SortsSearchQuery;
use App\Support\Listing\FiltersSearchQuery;

class Search extends BaseSearch
{
    use FiltersSearchQuery,
        SortsSearchQuery;

    protected $listing_id;

    protected $paginated;

    public function __construct($queryArgs = [], $listing_id = null, $paginated = true)
    {
        $this->listing_id = $listing_id;
        parent::__construct($queryArgs);
        $this->paginated = $paginated;
    }

    /**
     * Executes the search.
     *
     * @return string
     */
    public function execute()
    {
        return $this->search();
    }

    /**
     * Executes the complex search.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    protected function complexSearch()
    {
        return $this->sharedQuery(Listing::query()->whereIn('id', $this->getScoutIds()));
    }

    /**
     * Get the scout ids
     *
     * @return array
     */
    protected function getScoutIds()
    {
        $raw = collect($this->addLocation(Listing::search($this->keyword))->raw());

        return collect($raw->get('hits', $raw->get('results', [])))
        ->map->objectID
        ->toArray();
    }

    /**
     * Executes the simple search.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    protected function simpleSearch()
    {
        return $this->sharedQuery(Listing::query());
    }

    protected function sharedQuery($query)
    {
        $query = $this->addQueryFilters($query, $this->listing_id);
        $query = $this->addQuerySort($query);
        $query->published()->with(
            'user.favorites',
            'user.professionalInformation',
            'media',
            'spaces'
        );

        if ($this->paginated) {
            return $query->paginate(
                $this->perPage,
                $columns = ['*'],
                $pageName = 'page',
                $this->currentPage
            );
        }

        return $query->get();
    }

    protected function restrictionDisabled()
    {
        return (bool) config('app.disable_listing_search_publish_restriction');
    }
}
