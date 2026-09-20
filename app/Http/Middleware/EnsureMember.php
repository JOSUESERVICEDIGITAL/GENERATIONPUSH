<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMember
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'Member') {
            abort(403, 'Accès réservé aux membres.');
        }

        if ($user->status !== 'active') {
            abort(403, 'Votre compte membre n’est pas encore actif.');
        }

        return $next($request);
    }
}