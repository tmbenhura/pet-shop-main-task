<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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

        return response()->json(
            [
                'success' => true,
            ]
        );
    }
}
