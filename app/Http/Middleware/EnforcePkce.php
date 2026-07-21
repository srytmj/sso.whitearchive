<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforcePkce
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && $request->routeIs('passport.authorizations.authorize')) {
            if (empty($request->query('code_challenge'))) {
                return response()->json([
                    'error' => 'invalid_request',
                    'error_description' => 'code_challenge is required.',
                ], 400);
            }

            if ($request->query('code_challenge_method', 'S256') !== 'S256') {
                return response()->json([
                    'error' => 'invalid_request',
                    'error_description' => 'Only S256 code_challenge_method is supported.',
                ], 400);
            }
        }

        return $next($request);
    }
}
