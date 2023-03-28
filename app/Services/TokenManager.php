<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use DateTimeImmutable;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use App\Contracts\JwtTokenManager;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;

class TokenManager implements JwtTokenManager
{
    private Configuration $configuration;

    public function __construct()
    {
        $this->configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(base_path(config('jwt.signing_key_filename'))),
            InMemory::plainText(config('jwt.verification_key'))
        );

        $this->configuration->setValidationConstraints(
            new Constraint\SignedWith($this->configuration->signer(), $this->configuration->signingKey()),
            new Constraint\StrictValidAt(SystemClock::fromUTC()),
            new Constraint\IssuedBy(config('app.url'))
        );
    }

    /**
     * Issue token
     *
     * @param $claims array<int, array<string, mixed>>
     */
    public function issueToken(
        string $issuer,
        DateTimeImmutable $expiryDate,
        array $claims
    ): string {
        $now = new DateTimeImmutable();
        $builder = $this->configuration->builder()
            ->issuedBy($issuer)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($expiryDate);

        foreach ($claims as $claim => $value) {
            $builder = $builder->withClaim($claim, $value);
        }

        return $builder->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
    }

    /**
     * Get token claim
     */
    public function getTokenClaim(string $token, string $claim): mixed
    {
        try {
            $token = $this->configuration->parser()->parse($token);
        } catch (Exception $e) {
            return null;
        }

        return $token->claims()->get($claim);
    }
}
