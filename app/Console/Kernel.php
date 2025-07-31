<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Console Kernel for Azka Garden E-Commerce Application
 * Updated: 2025-07-31 15:08:05 by DenuJanuari
 * Enhanced command scheduling for e-commerce operations
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<class-string>
     */
    protected $commands = [
        \App\Console\Commands\TestMiddleware::class,
        \App\Console\Commands\ExpireOrders::class,
        \App\Console\Commands\SessionCleanupCommand::class, // Added session cleanup
    ];

    /**
     * Define the application's command schedule.
     * Updated: 2025-07-31 15:08:05 by DenuJanuari
     * Enhanced scheduling for e-commerce operations
     */
    protected function schedule(Schedule $schedule): void
    {
        // ==================================================
        // ORDER MANAGEMENT SCHEDULING
        // Updated: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Expire pending orders every minute (critical for e-commerce)
        $schedule->command('orders:expire')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground()
            ->onFailure(function () {
                \Log::error('Order expiration command failed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari',
                    'command' => 'orders:expire'
                ]);
            });

        // ==================================================
        // SESSION MANAGEMENT SCHEDULING
        // Updated: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Clean up expired sessions daily at 2 AM
        $schedule->command('session:cleanup --hours=48')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                \Log::info('Daily session cleanup completed successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari',
                    'hours' => 48
                ]);
            })
            ->onFailure(function () {
                \Log::error('Daily session cleanup failed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            });

        // Clean up very old sessions weekly (Sunday at 3 AM)
        $schedule->command('session:cleanup --hours=168') // 1 week
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                \Log::info('Weekly session cleanup completed successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari',
                    'hours' => 168
                ]);
            });

        // ==================================================
        // CART MANAGEMENT SCHEDULING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Clean up abandoned carts every 6 hours
        $schedule->command('cart:cleanup --abandoned --hours=24')
            ->everySixHours()
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                \Log::info('Cart cleanup completed successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari',
                    'type' => 'abandoned_carts'
                ]);
            });

        // ==================================================
        // DATABASE MAINTENANCE SCHEDULING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Optimize database tables weekly (Sunday at 4 AM)
        $schedule->command('db:optimize')
            ->weekly()
            ->sundays()
            ->at('04:00')
            ->withoutOverlapping()
            ->environments(['production'])
            ->onSuccess(function () {
                \Log::info('Database optimization completed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            });

        // ==================================================
        // LOG MANAGEMENT SCHEDULING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Clean up old log files monthly
        $schedule->command('log:clear --days=30')
            ->monthly()
            ->withoutOverlapping()
            ->onSuccess(function () {
                \Log::info('Log cleanup completed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari',
                    'retention_days' => 30
                ]);
            });

        // ==================================================
        // CACHE MANAGEMENT SCHEDULING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Clear view cache daily at midnight
        $schedule->command('view:clear')
            ->dailyAt('00:00')
            ->environments(['production'])
            ->onSuccess(function () {
                \Log::info('View cache cleared successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            });

        // ==================================================
        // MIDDLEWARE TESTING SCHEDULING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Test middleware functionality daily (development only)
        $schedule->command('test:middleware')
            ->dailyAt('01:00')
            ->environments(['local', 'testing'])
            ->withoutOverlapping()
            ->onSuccess(function () {
                \Log::info('Middleware test completed successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            })
            ->onFailure(function () {
                \Log::error('Middleware test failed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            });

        // ==================================================
        // BACKUP SCHEDULING (if backup commands exist)
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Daily database backup at 1 AM (production only)
        $schedule->command('backup:run --only-db')
            ->dailyAt('01:00')
            ->environments(['production'])
            ->withoutOverlapping()
            ->onSuccess(function () {
                \Log::info('Database backup completed successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            })
            ->onFailure(function () {
                \Log::error('Database backup failed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            });

        // ==================================================
        // HEALTH CHECK SCHEDULING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Application health check every 15 minutes
        $schedule->command('health:check')
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::critical('Application health check failed', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari',
                    'alert' => 'immediate_attention_required'
                ]);
            });

        // ==================================================
        // PERFORMANCE MONITORING
        // Added: 2025-07-31 15:08:05 by DenuJanuari
        // ==================================================

        // Generate performance reports weekly
        $schedule->command('performance:report')
            ->weekly()
            ->mondays()
            ->at('09:00')
            ->environments(['production'])
            ->onSuccess(function () {
                \Log::info('Performance report generated successfully', [
                    'timestamp' => '2025-07-31 15:08:05',
                    'user' => 'DenuJanuari'
                ]);
            });
    }

    /**
     * Register the commands for the application.
     * Updated: 2025-07-31 15:08:05 by DenuJanuari
     */
    protected function commands(): void
    {
        // Load all commands from Commands directory
        $this->load(__DIR__ . '/Commands');

        // Load console routes
        require base_path('routes/console.php');

        // Log command registration
        \Log::debug('Console commands registered successfully', [
            'timestamp' => '2025-07-31 15:08:05',
            'user' => 'DenuJanuari',
            'commands_loaded' => count($this->commands)
        ]);
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     * Added: 2025-07-31 15:08:05 by DenuJanuari
     */
    protected function scheduleTimezone(): string
    {
        return config('app.timezone', 'Asia/Jakarta');
    }

    /**
     * Define the application's command schedule cache.
     * Added: 2025-07-31 15:08:05 by DenuJanuari
     */
    protected function scheduleCache(): string
    {
        return storage_path('framework/schedule-' . sha1(base_path()) . '.cache');
    }
}
