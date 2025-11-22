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
        $middleware->trustProxies(at: '*');

        // 1. Pasang Bepsvpt (Untuk HSTS, X-Frame, No-Sniff, dll)
        $middleware->append(\Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class);

        // 2. Pasang Spatie (Khusus untuk CSP yang ribet)
        $middleware->append(\Spatie\Csp\AddCspHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
