<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SalesOrderCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     * * * * * * php artisan schedule:run >> /dev/null 2>&1
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('salesOrder:cron')->timezone('America/Sao_Paulo')->everyMinute();
    }
}
