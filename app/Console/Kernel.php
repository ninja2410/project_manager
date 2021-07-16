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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\OverdueChecks::class,
        \App\Console\Commands\WeeklySettlement::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
//        $schedule->command('overdue:checks')->dailyAt('03:00');
//        $schedule->command('overdue:weekly')->dailyAt('03:00');
        $schedule->command('overdue:checks')->everyMinute();
        $schedule->command('command:weekly')->everyMinute();
    }
}
