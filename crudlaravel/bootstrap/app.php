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
    ->withMiddleware(function (Middleware $middleware): void {
        // Render termina HTTPS en su proxy y reenvía HTTP al contenedor.
        // Sin esto Laravel genera las URLs de formularios en http:// y
        // Chrome muestra "The information you're about to submit is not secure".
        // Al confiar en el proxy se respeta X-Forwarded-Proto y todo sale en https://.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
