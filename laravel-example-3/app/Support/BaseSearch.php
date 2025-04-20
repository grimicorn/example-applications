<?php

namespace App\Support;

use App\Support\GetsCoordinates;

abstract class BaseSearch
{
    use GetsCoordinates;

    protected $perPage;
    protected $keyword;
    protected $sortOrder;
    protected $zipcode;
    protected $zipcodeRadius;
    protected $queryArgs;

    public function __construct($queryArgs = [])
    {
        $this->queryArgs = collect($queryArgs);
        $this->perPage = $this->queryArgs->get('per_page', 25);
        $this->currentPage = request()->get('page', 1);
        $this->keyword = $this->queryArgs->get('keyword');
        $this->sortOrder = $this->queryArgs->get('sort_order');
        $this->zipcode = $this->queryArgs->get('zip_code');
        $this->zipcodeRadius = intval($this->queryArgs->get('zip_code_radius', 25));
        $this->useSimple = (!$this->keyword && !$this->zipcode);
    }

    /**
     * Executes the search.
     *
     * @return string
     */
    abstract public function execute();

    /**
     * Executes the complex search.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    abstract protected function complexSearch();

    /**
     * Executes the simple search.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    abstract protected function simpleSearch();

    /**
     * Gets the search
     *
     * @return Collection
     */
    protected function search()
    {
        return $this->useSimple ? $this->simpleSearch() : $this->complexSearch();
    }

    /**
     * Adds the location filter if we are searching by zipcode.
     */
    protected function addLocation($search)
    {
        if (!$this->zipcode) {
            return $search;
        }

        $coordinates = $this->getSearchCoordinates();
        if ($coordinates) {
            $search->aroundLatLng(
                $coordinates['lat'] ?? null,
                $coordinates['lng'] ?? null,
                $this->zipcodeRadius
            );
        }

        return $search;
    }

    public function getSearchCoordinates()
    {
        if (!$this->zipcode) {
            return [];
        }

        return $this->getZipCodeCoordinates($this->zipcode);
    }
}
