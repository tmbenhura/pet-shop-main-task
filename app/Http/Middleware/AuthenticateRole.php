<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->bearerToken()) {
            return response()->json(
                [
                    'errors' => [
                        [
                            'status' => '401',
                            'title' => 'Unauthorized',
                        ]
                    ]
                ],
                401
            );
        }

        return $next($request);
    }
}
