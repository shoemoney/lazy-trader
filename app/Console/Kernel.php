<?php

namespace App\Console;

use App\Console\Commands\ActivateMarkets;
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
        ActivateMarkets::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('lazy-trader:fetch-current-pricing-all')->hourly();
        $schedule->command('cryptocompare:fetch-prices')->hourly();
        $schedule->command('cryptocompare:fetch-news')->daily();
        $schedule->command('cryptocompare:fetch-news-by-source')->daily();
        $schedule->command('cryptocompare:fetch-exchanges')->weekly();
        $schedule->command('cryptocompare:fetch-markets')->weekly();
        $schedule->command('cryptocompare:fetch-news-sources')->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
