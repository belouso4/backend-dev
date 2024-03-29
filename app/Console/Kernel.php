<?php

namespace App\Console;

use App\Console\Commands\MeiliSearch\DeleteAllIndexesCommand;
use App\Console\Commands\MeiliSearch\FlushModelsCommand;
use App\Console\Commands\MeiliSearch\ImportModelsCommand;
use App\Console\Commands\StatusUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\UserStatusJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        StatusUpdate::class,
        ImportModelsCommand::class,
        FlushModelsCommand::class,
        DeleteAllIndexesCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('users:StatusUpdate')->everyMinute();
        $schedule->job(new UserStatusJob)->everyMinute();
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
