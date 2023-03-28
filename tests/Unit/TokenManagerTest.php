<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\JwtTokenManager;
use App\Services\TokenManager;
use PHPUnit\Framework\TestCase;

class TokenManagerTest extends TestCase
{
    /**
     * Token manager implements jwt token manager interface.
     */
    public function test_token_manager_implements_jwt_token_manager_interface(): void
    {
        $this->assertTrue(in_array(JwtTokenManager::class, class_implements(TokenManager::class)));
    }
}
