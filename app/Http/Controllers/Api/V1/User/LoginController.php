<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Contracts\JwtTokenManager;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends Controller
{
    /**
     * Login into api
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!User::where('email', $request->email)->where('is_admin', false)->exists()) {
            return response()->json(
                [
                    'errors' => [
                        [
                            'status' => '401',
                            'title' => 'Failed to authenticate user',
                        ]
                    ]
                ],
                401
            );
        }

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'errors' => [
                        [
                            'status' => '401',
                            'title' => 'Failed to authenticate user',
                        ]
                    ]
                ],
                401
            );
        }

        $token = app(JwtTokenManager::class)->issueToken(
            config('app.url'),
            CarbonImmutable::now()->addHours(1),
            [
                'user_uuid' => $user->uuid,
                'roles' => ['user'],
            ]
        );

        return response()->json(
            [
                'data' => [
                    'token' => $token,
                ]
            ]
        );
    }
}
