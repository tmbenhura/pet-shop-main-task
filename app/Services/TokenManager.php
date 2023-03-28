<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\JwtTokenManager;
use DateTimeImmutable;

class TokenManager implements JwtTokenManager
{
    /**
     * Issue token
     */
    public function issueToken(
        string $issuer,
        DateTimeImmutable $expiryDate,
        array $claims
    ): string {
        return '';
    }

    /**
     * Get token claim
     */
    public function getTokenClaim(string $token, string $claim): mixed
    {
        return null;
    }
}
