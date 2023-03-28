<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends Controller
{
    /**
     * Login into api
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!User::where('email', $request->email)->exists()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to authenticate user',
                ],
                401
            );
        }

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to authenticate user',
                ],
                401
            );
        }

        $token = $this->createTokenForUser($user);

        return response()->json(
            [
                'data' => [
                    'token' => $token,
                ]
            ]
        );
    }

    public function createTokenForUser(User $user): string
    {
        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(base_path('file.pem')),
            InMemory::plainText('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
        );

        $now = new DateTimeImmutable();
        $token = $configuration->builder()
                ->issuedBy(config('app.url'))
                ->issuedAt($now)
                ->canOnlyBeUsedAfter($now)
                ->expiresAt($now->modify('+1 hour'))
                ->getToken($configuration->signer(), $configuration->signingKey());

        return $token->toString();
    }
}
