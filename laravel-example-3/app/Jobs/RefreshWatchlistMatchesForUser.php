<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Support\Watchlist\WatchlistMatches;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshWatchlistMatchesForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    protected $watchlistMatches;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, WatchlistMatches $watchlistMatches)
    {
        $this->user = $user;
        $this->watchlistMatches = $watchlistMatches;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->watchlistMatches->refreshForUser($this->user);
    }
}
