<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registers short alias names for each role middleware.
        // Usage in routes: middleware('admin'), middleware('nurse'), middleware('doctor')
        $middleware->alias([
            'admin'  => \App\Http\Middleware\AdminMiddleware::class,
            'nurse'  => \App\Http\Middleware\NurseMiddleware::class,
            'doctor' => \App\Http\Middleware\DoctorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();