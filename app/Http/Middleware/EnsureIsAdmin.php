<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request. Only admins may proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! (bool) ($user->is_admin ?? false)) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
