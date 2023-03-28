<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\JwtTokenManager;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

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
        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(base_path(config('jwt.signing_key_filename'))),
            InMemory::plainText(config('jwt.verification_key'))
        );

        $now = new DateTimeImmutable();
        $builder = $configuration->builder()
            ->issuedBy($issuer)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($expiryDate);

        foreach ($claims as $claim => $value) {
            $builder = $builder->withClaim($claim, $value);
        }

        return $builder->getToken($configuration->signer(), $configuration->signingKey())
            ->toString();
    }

    /**
     * Get token claim
     */
    public function getTokenClaim(string $token, string $claim): mixed
    {
        return null;
    }
}
