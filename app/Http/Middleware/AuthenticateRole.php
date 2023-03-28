<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Contracts\JwtTokenManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            return $this->unauthorisedResponse();
        }

        $claimedRoles = app(JwtTokenManager::class)->getTokenClaim($request->bearerToken(), 'roles');

        if (!$claimedRoles) {
            return $this->unauthorisedResponse();
        }

        if (!in_array($role, $claimedRoles)) {
            return $this->unauthorisedResponse();
        }

        return $next($request);
    }

    private function unauthorisedResponse(): JsonResponse
    {
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
}
