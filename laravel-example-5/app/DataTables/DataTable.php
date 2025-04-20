<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder;

abstract class DataTable
{
    public function get()
    {
        return $this->query()->paginate();
    }

    abstract protected function query();

    abstract protected function searchableColumns();

    protected function withSearch(Builder $query)
    {
        if (!request()->has('keyword')) {
            return $query;
        }

        return $query->whereLike(
            $this->searchableColumns(),
            request()->get('keyword')
        );
    }
}
