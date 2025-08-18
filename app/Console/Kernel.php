<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoSubmitExpiredTryoutsCommand;
use App\Console\Commands\ClearOldTryoutSessions;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AutoSubmitExpiredTryoutsCommand::class,
        ClearOldTryoutSessions::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Auto-submit expired tryout sessions every minute
        $schedule->command('tryouts:auto-submit-expired')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // Clear old tryout sessions weekly
        $schedule->command('tryouts:clear-old-sessions --days=30')
            ->weekly()
            ->sundays()
            ->at('02:00');

        // Clear application cache daily
        $schedule->command('cache:clear')
            ->daily()
            ->at('03:00');

        // You can add more scheduled tasks here
        // Example: Generate reports, backup data, etc.
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}