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
        // Chạy mỗi phút để kiểm tra các đặt ghế quá hạn (5 phút)
        $schedule->command('bookings:cancel-expired --minutes=5')->everyMinute();
        // Dọn refresh token hết hạn / đã revoke mỗi đêm lúc 3:00 AM
        $schedule->command('tokens:clean-expired')->dailyAt('03:00');
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
