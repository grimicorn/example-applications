<?php

namespace App\Observers;

use App\Site;

class SiteObserver
{
    /**
     * Handle the site "saved" event.
     *
     * @param  \App\Site  $site
     * @return void
     */
    public function saved(Site $site)
    {
        $site->syncPages();
    }

    /**
     * Handle the site "deleting" event.
     *
     * @param  \App\Site  $site
     * @return void
     */
    public function deleting(Site $site)
    {
        $site->pages->each->delete();
    }
}
