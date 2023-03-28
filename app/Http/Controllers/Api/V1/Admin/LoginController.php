<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseLoginController;

class LoginController extends BaseLoginController
{
    /**
     * Check user existence
     */
    public function userExists(Request $request): bool
    {
        return User::where('email', $request->email)->where('is_admin', true)->exists();
    }
}
