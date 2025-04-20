<?php

namespace App\DataTables;

use App\Site;
use App\SitePage;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class SitePageDataTable extends DataTable
{
    protected $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function query()
    {
        $query = $this->withSearch(
            SitePage::forSite($this->site)
        );

        return QueryBuilder::for($query)
            ->with('site');
    }

    protected function searchableColumns()
    {
        return ['url'];
    }
}
