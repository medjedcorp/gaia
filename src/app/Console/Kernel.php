<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ImportReCsv;
use App\Console\Commands\DelLand;
use App\Console\Commands\Hello;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ImportReCsv::class,
        DelLand::class,
        Hello::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ImportReCsv::class)->dailyAt('12:00'); 
        $schedule->command(ImportReCsv::class)->dailyAt('22:00'); 
        $schedule->command(DelLand::class)->dailyAt('12:05'); 
        $schedule->command(DelLand::class)->dailyAt('22:05'); 
        $schedule->command(Hello::class)->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
