<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Personne non connecté
        |--------------------------------------------------------------------------
        |
        | On ne révèle pas l'existence du back-office.
        |
        */

        if (! $request->user()) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Utilisateur connecté mais non administrateur
        |--------------------------------------------------------------------------
        */

        if (! $request->user()->is_admin) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Compte administrateur désactivé
        |--------------------------------------------------------------------------
        */

        if (! $request->user()->is_active) {
            abort(404);
        }

        return $next($request);
    }
}
