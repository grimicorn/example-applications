<?php

namespace App\DataTables;

use App\Site;
use App\User;
use Spatie\QueryBuilder\QueryBuilder;

class SiteDataTable extends DataTable
{
    protected $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user ?? auth()->user();
    }

    public function query()
    {
        $query = $this->withSearch(
            Site::forUser($this->user)
        );

        return QueryBuilder::for($query);
    }

    protected function searchableColumns()
    {
        return ['sitemap_url', 'name'];
    }
}
