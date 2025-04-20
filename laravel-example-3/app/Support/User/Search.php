<?php

namespace App\Support\User;

use App\User;
use App\Support\BaseSearch;
use App\Support\GetsCoordinates;

class Search extends BaseSearch
{
    protected $occupation;

    public function __construct($queryArgs = [])
    {
        parent::__construct($queryArgs);
        $this->occupation = $this->queryArgs->get('occupation');
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
     * Sorts the search.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addQuerySort($query)
    {
        switch ($this->queryArgs->get('sort_order', 'title_a_to_z')) {
            case 'title_z_to_a':
                return $this->addCaseInsensitiveOrderByLastName($query, $ascending = false);
                break;

            default:
                return $this->addCaseInsensitiveOrderByLastName($query);
                break;
        }
    }

    /**
     * Executes the complex search.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    protected function complexSearch()
    {
        return $this->sharedQuery(
            User::whereIn('id', $this->getScoutIds())
        );
    }

    /**
     * Executes the simple search.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    protected function simpleSearch()
    {
        return $this->sharedQuery(User::query());
    }

    /**
     * Get the scout ids
     *
     * @return array
     */
    protected function getScoutIds()
    {
        $raw = collect($this->addLocation(User::search($this->keyword))->raw());
        return collect($raw->get('hits', $raw->get('results', [])))
        ->map->objectID
        ->toArray();
    }

    protected function sharedQuery($query)
    {
        $query->isListed();

        if ($this->occupation) {
            $query->join('user_professional_informations', 'users.id', '=', 'user_professional_informations.user_id');
            $query->where('user_professional_informations.occupation', $this->occupation);
        }

        $query = $this->addQuerySort($query);
        $query->with('professionalInformation');

        return $query
        ->paginate(
            $perPage = $this->perPage,
            $columns = ['users.*'],
            $pageName = 'page',
            $page = $this->currentPage
        );
    }

    protected function addCaseInsensitiveOrderByLastName($query, $ascending = true)
    {
        $order = $ascending ? 'ASC' : 'DESC';
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        $query->orderByRaw(
            ($driver === 'sqlite') ? "LAST_NAME COLLATE NOCASE {$order}" : "last_name {$order}"
        );

        return $query;
    }
}
