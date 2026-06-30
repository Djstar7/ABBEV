<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restreint l'accès aux routes web du panel selon le rôle.
 *
 * Usage : ->middleware('role:admin') ou ->middleware('role:admin,producer').
 * - Non authentifié → redirigé vers la page de connexion admin.
 * - Authentifié mais rôle non autorisé → 403.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Accès réservé à votre rôle.');
        }

        return $next($request);
    }
}
