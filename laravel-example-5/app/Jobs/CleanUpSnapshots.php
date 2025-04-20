<?php

namespace App\Jobs;

use App\SitePage;
use Illuminate\Bus\Queueable;
use App\SnapshotConfiguration;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CleanUpSnapshots implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $configuration;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SnapshotConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Clean up baselines
        $baselines = $this->configuration
            ->snapshots()
            ->baseline()
            ->get();

        if ($baselines->count() > 1) {
            $baselines->slice(1)->each->delete();
        }

        // Cleanup non-baselines
        $nonBaselines = $this->configuration
            ->snapshots()
            ->notBaseline()
            ->get();
        if ($nonBaselines->count() > 3) {
            $nonBaselines->slice(3)->each->delete();
        }
    }
}
