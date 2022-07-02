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
        $schedule->command(ImportReCsv::class)->dailyAt('12:30'); 
        // $schedule->command(ImportReCsv::class)->dailyAt('11:45'); 
        // $schedule->command(ImportReCsv::class)->dailyAt('14:00'); 
        // $schedule->command(ImportReCsv::class)->dailyAt('20:30'); 
        $schedule->command(DelLand::class)->dailyAt('12:40'); 
        // $schedule->command(DelLand::class)->dailyAt('21:45'); 
        // $schedule->command(Hello::class)->everyMinute();
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
