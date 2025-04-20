<?php

namespace App\Jobs;

use App\SavedSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Support\Watchlist\WatchlistMatches;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshWatchlistMatchesForUserSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $search;

    protected $watchlistMatches;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SavedSearch $search, WatchlistMatches  $watchlistMatches)
    {
        $this->search = $search;
        $this->watchlistMatches = $watchlistMatches;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $result = $this->watchlistMatches->forUserSearch($this->search);

            if (config('app.debug')) {
                Log::info(collect([
                    "Watchlist {$this->search->name} (saved_search_id:{$this->search->id})",
                    'refreshed for',
                    "{$this->search->user->name} (user_id:{$this->search->user->id})",
                    "matches found:",
                    $result->listings()->map(function ($match) {
                        return "{$match->title} (listing_id:{$match->id})";
                    })->implode(','),
                ])->implode(' '));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
