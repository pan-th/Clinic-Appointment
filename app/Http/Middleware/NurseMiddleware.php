<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NurseMiddleware
{
    /**
     * Blocks any user who is not a nurse from accessing nurse-only pages.
     * If the logged-in user's role is not 'nurse', they get a 403 Forbidden error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isNurse()) {
            return $next($request);
        }

        abort(403, 'Access denied. This page is for nurses only.');
    }
}