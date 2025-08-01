<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\RunHealthChecksCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:crontab-check')->everyMinute();

        $customSchedulerPath = app_path('Console/CustomScheduler.php');

        if (file_exists($customSchedulerPath)) {
            require_once $customSchedulerPath;
            CustomScheduler::scheduleTasks($schedule);
        }

        $schedule->command('app:check-coingate-command')->everyFiveMinutes();

        $schedule->command('app:check-razorpay-command')->everyFiveMinutes();

        $schedule->command('subscription:check-end')->everyFiveMinutes();

        $schedule->command('app:check-yookassa-command')->daily();

        $schedule->command('app:clear-user-open-a-i')->daily();

        $schedule->command('app:clear-user-open-a-i-chat')->daily();

        $schedule->command('app:clear-job-table')->daily();

        $schedule->command('app:clear-user-activity')->daily();

        $schedule->command('app:clear-ai-realtime-image')->daily();

        $schedule->command('app:test-command')->everyMinute();
    }

    // $schedule->command(RunHealthChecksCommand::class)->everyFiveMinutes();
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
