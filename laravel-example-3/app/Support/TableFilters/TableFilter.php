<?php

namespace App\Support\TableFilters;

use App\User;
use Illuminate\Http\Request;

abstract class TableFilter
{
    /**
     * The current request.
     *
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Gets all filtered results.
     *
     * @return Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->query()->get();
    }

    /**
     * Builds up the filter query.
     *
     * @return Builder
     */
    abstract protected function query();

    /**
     * Gets the filter paginated.
     *
     * @param  integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function paginated($perPage = 10)
    {
        return $this->query()->paginate($perPage);
    }

    /**
     * Gets the sort key.
     *
     * @return string
     */
    abstract public function getSortKey();

    /**
     * Gets the sort order.
     *
     * @return string
     */
    protected function getSortOrder()
    {
        return $this->request->get('sortOrder', 'asc');
    }
}
