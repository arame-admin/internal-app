<?php

use App\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        // Run timesheet reminder check daily at 9 AM
        $schedule->command('timesheet:check-missed')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Also run every hour to clean up expired reminders
        $schedule->command('timesheet:cleanup-reminders')
            ->hourly()
            ->withoutOverlapping();
    })
    ->create();
