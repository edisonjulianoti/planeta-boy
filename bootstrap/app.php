<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__."/../routes/web.php",
        commands: __DIR__."/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: "*");
        $middleware->redirectGuestsTo(fn () => route("login"));
        $middleware->redirectUsersTo(fn () => route("home"));
        $middleware->validateCsrfTokens([
            "/entrar",
            "/sair",
            "/login",
            "/registro",
            "/cadastro",
            "/logout",
            "/perfis/*",
            "/assinar*",
            "/assinaturas/*",
            "/subscribe*",
            "/admin/*",
            "/user/*",
            "/planos/*",
            "/api/*",
        ]);
        $middleware->alias([
            "admin" => \App\Http\Middleware\EnsureUserIsAdmin::class,
            "age.gate" => \App\Http\Middleware\AgeGateMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
