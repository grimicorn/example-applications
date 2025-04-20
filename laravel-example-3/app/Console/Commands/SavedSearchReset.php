<?php

namespace App\Console\Commands;

use App\Listing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\ConfirmableTrait;

class SavedSearchReset extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fe:saved-search:reset
                            {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->confirmToProceed();

        $this->warn('Migrating the database');
        Artisan::call('migrate:fresh', ['--force' => null]);
        $this->info('Database migrated');

        $this->warn('Seeding the database with WatchlistRefreshStorySeeder1');
        (new \WatchlistRefreshStorySeeder1)->run();
        $this->info('Seeding complete');

        $this->warn('Starting the watchlist matches refresh');
        Artisan::call('fe:watchlist-matches:refresh');
        $this->info('Watchlist matches refresh started');
    }
}
