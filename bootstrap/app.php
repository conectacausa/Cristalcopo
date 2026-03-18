<?php

use App\Http\Middleware\EnsureUserCanAccessScreen;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request) {
            return route('auth.login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            return route('auth.redirect');
        });

        $middleware->alias([
            'user.active' => EnsureUserIsActive::class,
            'screen' => EnsureUserCanAccessScreen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
