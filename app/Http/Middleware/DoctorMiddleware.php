<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorMiddleware
{
    /**
     * Blocks any user who is not a doctor from accessing doctor-only pages.
     * If the logged-in user's role is not 'doctor', they get a 403 Forbidden error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isDoctor()) {
            return $next($request);
        }

        abort(403, 'Access denied. This page is for doctors only.');
    }
}