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
        // Send appointment reminders 1 day before (runs daily at 6 AM)
        $schedule->command('appointments:send-reminders', ['--days=1'])
            ->dailyAt('06:00')
            ->name('send-appointment-reminders-1day')
            ->withoutOverlapping();

        // Process pending SMS queue every minute (for testing)
        // Change to ->everyFiveMinutes() for production
        $schedule->command('sms:process', ['--limit=100'])
            ->everyMinute()
            ->name('process-sms-queue')
            ->withoutOverlapping();

        // Optionally: Retry failed SMS every 30 minutes
        $schedule->command('sms:process', ['--limit=50', '--retry'])
            ->everyThirtyMinutes()
            ->name('retry-failed-sms')
            ->withoutOverlapping();

        // Optionally: Send reminders 2 days before as well
        // $schedule->command('appointments:send-reminders', ['--days=2'])
        //     ->dailyAt('08:00')
        //     ->name('send-appointment-reminders-2days')
        //     ->withoutOverlapping();
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
