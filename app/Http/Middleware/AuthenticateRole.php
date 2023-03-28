<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Contracts\JwtTokenManager;
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
                        ],
                    ],
                ],
                401
            );
        }

        $claimedRoles = app(JwtTokenManager::class)->getTokenClaim($request->bearerToken(), 'roles');

        if (!$claimedRoles) {
            return response()->json(
                [
                    'errors' => [
                        [
                            'status' => '401',
                            'title' => 'Unauthorized',
                        ],
                    ],
                ],
                401
            );
        }

        if (!in_array($role, $claimedRoles)) {
            return response()->json(
                [
                    'errors' => [
                        [
                            'status' => '401',
                            'title' => 'Unauthorized',
                        ],
                    ],
                ],
                401
            );
        }

        return $next($request);
    }
}
