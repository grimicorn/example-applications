<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run the canceled subscriptions check a little after midnight.
        $schedule->command('fe:canceled-subscriptions')->dailyAt('00:10')->onOneServer()->withoutOverlapping();

        // Send the Due Diligence Digest daily
        $schedule->command('fe:send-due-diligence-digest')->daily()->onOneServer()->withoutOverlapping();

        // Schedule the Watchlist Refresh
        // $schedule->command('fe:watchlist-matches:refresh')->dailyAt('05:00')->onOneServer()->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
