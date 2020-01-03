<?php

namespace App\Console;

use App\Extensions\Common\Common;
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
        Commands\Apidoc::class,
        Commands\Inspire::class,
        Commands\TestRoute::class,
        Commands\DumpStructs::class,
        Commands\Emqtt::class,
        Commands\Tools::class,
        Commands\Upgrade::class,
        Commands\TopicLogger::class,
        Commands\SendEmailCmd::class,
        Commands\SendAnotherEmailCmd::class,
        Commands\MakeTestTableCmd::class,
        Commands\TestTableSingle::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
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
