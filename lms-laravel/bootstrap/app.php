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
        // Middleware Package Bepsvpt (Biar tetap jalan kalau ada fitur spesifik yang dipakai)
        $middleware->append(\Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class);

        // Middleware CSP dari Spatie (Khusus CSP)
        $middleware->append(\Spatie\Csp\AddCspHeaders::class);
        // Ini akan menimpa/melengkapi header yang mungkin terlewat oleh package lain
        $middleware->append(\App\Http\Middleware\EnsureSecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
