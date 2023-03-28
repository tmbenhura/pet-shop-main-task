<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Contracts\JwtTokenManager;
use App\Services\TokenManager;
use Tests\TestCase;

class ResolveTokenManagerTest extends TestCase
{
    /**
     * Container resolves token manager for jwt token manager interface query.
     */
    public function test_container_resolves_token_manager_for_jwt_token_manager_interface_query(): void
    {
        $resolveManager = app(JwtTokenManager::class);

        $this->assertTrue($resolveManager instanceof TokenManager);
    }
}
