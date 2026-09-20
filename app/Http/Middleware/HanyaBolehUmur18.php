<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HanyaBolehUmur18
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika umur < 18 (dibawah umur), Tendang ke halaman 403 (Forbidden)
        if (auth()->user()->age < 18) {
            abort(403, 'Maaf, hanya pengguna berusia minimal 18 tahun yang dapat mengakses halaman ini.');
        }

        // Jika lolos, biarkan masuk ke Controller
        return $next($request);
    }
}
