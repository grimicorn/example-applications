<?php

namespace App\Jobs;

use App\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSiteSnapshotUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $site;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
        $this->site->processing = now();
        $this->site->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->causedBy($this->site)->log('Snapshot update processing started for site :causer.id');
    }
}
