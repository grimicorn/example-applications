<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\User\CanceledSubscriptions;

class CheckRecentCanceledSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fe:canceled-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks recent canceled subscriptions and applies an actions that need to happen such as unpublishing listings';

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
        // Unpublish canceled subscription listings.
        (new CanceledSubscriptions)->unpublishListings();
    }
}
