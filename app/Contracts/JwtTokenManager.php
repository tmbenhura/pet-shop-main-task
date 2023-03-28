<?php

declare(strict_types=1);

namespace App\Contracts;

use DateTimeImmutable;

interface JwtTokenManager
{
    /**
     * Issue token
     */
    public function issueToken(
        string $issuer,
        DateTimeImmutable $expiryDate,
        array $claims
    ): string;

    /**
     * Get token claim
     */
    public function getTokenClaim(string $token, string $claim): mixed;
}
