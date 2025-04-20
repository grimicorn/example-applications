<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Support\Watchlist\WatchlistMatches;
use App\Jobs\RefreshWatchlistMatchesForUser;

class WatchlistMatchesRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fe:watchlist-matches:refresh
                            {--user= : The id of the user to refresh watchlists for.}
                            {--dont-notify : Disable sending of notifications.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes all watchlists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $matches = new WatchlistMatches(
            $notify = !$this->option('dont-notify')
        );

        if ($this->option('user')) {
            $matches->refreshForUser(
                $user = User::findOrFail($this->option('user'))
            );

            return;
        }

        $matches->refreshForAllUsers();
    }
}
