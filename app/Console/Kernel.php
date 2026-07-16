<?php

declare(strict_types=1);

namespace App\Console;

use App\Jobs\ScanForDuplicatePersons;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    #[\Override]
    protected function schedule(Schedule $schedule): void
    {
        // Run duplicate scanning once a day (adjust frequency as needed)
        $schedule->job(new ScanForDuplicatePersons(0.7, 10))->daily();
    }

    /**
     * Register the commands for the application.
     */
    #[\Override]
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
