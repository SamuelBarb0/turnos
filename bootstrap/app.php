<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php', // Añadida la configuración de rutas API
    )
    // Dentro de bootstrap/app.php, busca la sección de middleware web
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // Añade aquí tu middleware si no existe
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
        
        // Añade configuración para middleware API
        $middleware->api(append: [
            // Middlewares específicos para API si los necesitas
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();