<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Contracts\JwtTokenManager;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
