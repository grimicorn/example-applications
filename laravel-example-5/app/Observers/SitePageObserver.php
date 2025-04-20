<?php

namespace App\Observers;

use App\SitePage;
use App\SnapshotConfiguration;

class SitePageObserver
{
    /**
     * Handle the site page "created" event.
     *
     * @param  \App\SitePage  $page
     * @return void
     */
    public function created(SitePage $page)
    {
        SnapshotConfiguration::firstOrCreate([
            'site_page_id' => $page->id,
            'width' => 1400,
        ]);
    }

    /**
     * Handle the site page "saving" event.
     *
     * @param  \App\SitePage  $page
     * @return void
     */
    public function saving(SitePage $page)
    {
        if (is_null($page->difference_threshold)) {
            $page->difference_threshold = $page->site->difference_threshold;
        }
    }

    /**
     * Handle the site page "deleting" event.
     *
     * @param  \App\SitePage  $page
     * @return void
     */
    public function deleting(SitePage $page)
    {
        $page->snapshotConfigurations()->get()->each->delete();
    }
}
