<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgeGateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário já confirmou a idade
        if (!$request->cookie('age_gate_confirmed')) {
            // Injetar variável para mostrar o age gate na view
            view()->share('show_age_gate', true);
        } else {
            view()->share('show_age_gate', false);
        }

        return $next($request);
    }
}
