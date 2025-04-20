<?php

namespace App\Support\Listing;

trait SortsSearchQuery
{
    protected function sort($search)
    {
        return $search->values();
    }

    protected function addQuerySort($query)
    {
        switch ($this->queryArgs->get('sort_order', 'lcs_high_to_low')) {
            case 'title_a_to_z':
                return $this->addCaseInsensitiveOrderByTitle($query);
                break;

            case 'title_z_to_a':
                return $this->addCaseInsensitiveOrderByTitle($query, $ascending = false);
                break;

            default:
                $query->orderBy('current_score_total', 'desc');
                return $query;
        }
    }

    protected function addCaseInsensitiveOrderByTitle($query, $ascending = true)
    {
        $order = $ascending ? 'ASC' : 'DESC';
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        $query->orderByRaw(
            ($driver === 'sqlite') ? "TITLE COLLATE NOCASE {$order}" : "title {$order}"
        );

        return $query;
    }
}
