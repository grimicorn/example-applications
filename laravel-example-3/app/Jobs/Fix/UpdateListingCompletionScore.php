<?php

namespace App\Jobs\Fix;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateListingCompletionScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $listings;

    public function __construct($listings)
    {
        $this->listings = $listings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->listings->each(function ($listing) {
            $listing->listingCompletionScore->save();
            Log::info("{$listing->title} completion score updated");
        });
    }
}
