<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for expired content - runs every hour
        $schedule->command('content:check-expired')->hourly();
         $schedule->command('app:update-referral-leaderboard')->everyTenMinutes();
        // Optional: Clean up old logs monthly
        // $schedule->command('logs:clean --days=90')->monthly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}