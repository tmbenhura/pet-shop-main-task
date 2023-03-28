<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Http\Request;
use App\Contracts\JwtTokenManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::viaRequest(
            'jwt',
            function (Request $request): ?User {
                if (!$request->bearerToken()) {
                    return null;
                }

                $userUuid = app(JwtTokenManager::class)->getTokenClaim($request->bearerToken(), 'user_uuid');

                return User::where('uuid', $userUuid)
                    ->first();
            }
        );
    }
}
