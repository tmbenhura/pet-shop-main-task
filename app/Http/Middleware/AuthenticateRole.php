<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
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

        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(base_path(config('jwt.signing_key_filename'))),
            InMemory::plainText(config('jwt.verification_key'))
        );

        $configuration->setValidationConstraints(
            new Constraint\SignedWith($configuration->signer(), $configuration->signingKey()),
            new Constraint\StrictValidAt(SystemClock::fromUTC()),
            new Constraint\IssuedBy(config('app.url'))
        );

        $token = $configuration->parser()->parse($request->bearerToken());
        $claimedRoles = $token->claims()->get('roles');

        if (!in_array($role, $claimedRoles)) {
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
