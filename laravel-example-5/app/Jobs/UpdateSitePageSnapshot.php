<?php

namespace App\Jobs;

use App\SitePage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSitePageSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SitePage $page)
    {
        $page->processing = now();
        $page->save();

        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->page->snapshotConfigurations->each->updateSnapshot();

        $this->page->processing = null;
        $this->page->save();
    }
}
