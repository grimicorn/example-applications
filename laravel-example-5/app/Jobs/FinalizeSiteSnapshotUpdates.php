<?php

namespace App\Jobs;

use App\Site;
use Illuminate\Bus\Queueable;
use App\Notifications\SiteNeedsReview;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FinalizeSiteSnapshotUpdates implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->site->processing = null;
        $this->site->save();

        if ($this->site->fresh()->needs_review) {
            $this->site->user->notify(new SiteNeedsReview($this->site));
        }

        activity()->causedBy($this->site)->log('Snapshot update processing completed for site :causer.id');
    }
}
