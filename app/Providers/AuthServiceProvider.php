<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;

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

                try {
                    $token = $configuration->parser()->parse($request->bearerToken());
                } catch (Exception $e) {
                    return null;
                }

                return User::where('uuid', $token->claims()->get('user_uuid'))
                    ->first();
            }
        );
    }
}
