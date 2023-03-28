<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use App\Contracts\JwtTokenManager;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseLoginController extends Controller
{
    /**
     * Check user existence
     */
    abstract public function userExists(Request $request): bool;

    /**
     * Login into api
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->userExists($request)) {
            return $this->authenticationFailureResponse();
        }

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return $this->authenticationFailureResponse();
        }

        return response()->json(
            [
                'data' => [
                    'token' => $this->tokenForUser($user),
                ],
            ]
        );
    }

    private function tokenForUser(User $user): string
    {
        return app(JwtTokenManager::class)->issueToken(
            config('app.url'),
            CarbonImmutable::now()->addHours(1),
            [
                'user_uuid' => $user->uuid,
                'roles' => $user->roles,
            ]
        );
    }

    private function authenticationFailureResponse(): JsonResponse
    {
        return response()->json(
            [
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Failed to authenticate user',
                    ],
                ],
            ],
            401
        );
    }
}
