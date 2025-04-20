<?php

namespace App\Support\TableFilters;

use App\Support\ExchangeSpace\MemberRole;

abstract class ExchangeSpaceFilter extends TableFilter
{
    protected $dateSortKey = 'created_at';

    /**
     * Gets the filter paginated.
     *
     * @param  integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function paginated($perPage = 10)
    {
        $results = $this->query()->get();
        $results = $this->searchResults($results);
        $results = $this->sortResults($results);
        $results = $this->filterResults($results);
        $results = $results->values()->paginate($perPage);

        return $results;
    }

    /**
     * Allows filtering of results before pagination.
     * Override to filter results.
     *
     * @param \Illuminate\Support\Collection $results
     * @return void
     */
    protected function filterResults($results)
    {
        return $results;
    }

    /**
     * Searches the shared column results.
     *
     * @param  Illuminate\Support\Collection $results
     * @param  \Closure $closure
     *
     * @return Illuminate\Support\Collection
     */
    protected function searchSharedColumnResults($results, $closure = null)
    {
        $search = $this->request->get('search');

        if (!$search) {
            return $results;
        }

        $results = $results->filter(function ($result) use ($search, $closure) {
            // Check buyer name.
            $result->members->where('role', MemberRole::BUYER);
            $buyer_name = (string) optional($this->getResultBuyerUser($result))->name;
            if (str_contains(strtolower($buyer_name), strtolower($search))) {
                return true;
            }

            // Check exchange space name.
            if (str_contains(strtolower($result->title), strtolower($search))) {
                return true;
            }

            if (is_callable($closure)) {
                return $closure($search, $result);
            }

            return false;
        });

        return $results;
    }

    /**
     * Searches the results.
     *
     * @param  Illuminate\Support\Collection $results
     *
     * @return Illuminate\Support\Collection
     */
    abstract protected function searchResults($results);

    /**
     * Sorts the results
     *
     * @param  Illuminate\Support\Collection $results
     *
     * @return Illuminate\Support\Collection
     */
    abstract protected function sortResults($results);

    /**
     * Sorts the shared column results
     *
     * @param  Illuminate\Support\Collection $results
     * @param  \Closure $closure
     *
     * @return Illuminate\Support\Collection
     */
    protected function sortSharedColumnResults($results, $closure = null)
    {
        switch ($sortKey = $this->getSortKey()) {
            case 'title':
                $results = $this->sortBySpaceTitle($results);
                break;

            case 'buyer_name':
                $results = $this->sortByBuyerName($results);
                break;

            case 'notifications':
                $results = $results->sortBy('notification_count');
                break;

            default:
                $results = $this->sortByDate($results);
                break;
        }

        if (is_callable($closure)) {
            $results = $closure($sortKey, $results);
        }

        return ($this->getSortOrder() === 'asc') ? $results : $results->reverse();
    }

    /**
     * Sort the results by the business name
     *
     * @param  Illuminate\Support\Collection $results
     *
     * @return Illuminate\Support\Collection
     */
    protected function sortBySpaceTitle($results)
    {
        // Sort the results
        $results = $results->sortBy('title');

        return $results;
    }

    /**
     * Sort the results by the date
     *
     * @param  Illuminate\Support\Collection $results
     *
     * @return Illuminate\Support\Collection
     */
    protected function sortByDate($results)
    {
        $key = $this->dateSortKey;

        // Sort the results
        $results = $results->sortBy(function ($result) use ($key) {
            return $result->$key;
        });

        return $results;
    }

    /**
     * Sort the results by the buyer name
     *
     * @param  Illuminate\Support\Collection $results
     *
     * @return Illuminate\Support\Collection
     */
    protected function sortByBuyerName($results)
    {
        // Sort the results
        $results = $results->sortBy(function ($result) {
            return optional($this->getResultBuyerUser($result))->name;
        });

        return $results;
    }

    /**
     * Gets the exchange space buy user from the result.
     *
     * @param  App\ExchangeSpace $result
     *
     * @return App\User || null
     */
    protected function getResultBuyerUser($result)
    {
        $members = $result->members;
        $buyers = optional($members)->where('role', MemberRole::BUYER);
        $buyer = optional($buyers)->first();

        return optional($buyer)->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getSortKey()
    {
        return $this->request->get('sortKey', $this->dateSortKey);
    }
}
