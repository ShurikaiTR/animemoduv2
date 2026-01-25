<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        // Akşam yayın saatlerinde (18:00 - 01:00) her 30 dakikada bir, diğer saatlerde saat başı çalışır.
        $schedule->command('anime:sync-episodes')->hourly()->unlessBetween('18:00', '01:00')->withoutOverlapping();
        $schedule->command('anime:sync-episodes')->everyThirtyMinutes()->between('18:00', '01:00')->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
