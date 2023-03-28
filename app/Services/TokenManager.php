<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\JwtTokenManager;
use DateTimeImmutable;
use Exception;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Validation\Constraint;

class TokenManager implements JwtTokenManager
{
    private $_configuration;

    public function __construct()
    {
        $this->_configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(base_path(config('jwt.signing_key_filename'))),
            InMemory::plainText(config('jwt.verification_key'))
        );

        $this->_configuration->setValidationConstraints(
            new Constraint\SignedWith($this->_configuration->signer(), $this->_configuration->signingKey()),
            new Constraint\StrictValidAt(SystemClock::fromUTC()),
            new Constraint\IssuedBy(config('app.url'))
        );
    }

    /**
     * Issue token
     */
    public function issueToken(
        string $issuer,
        DateTimeImmutable $expiryDate,
        array $claims
    ): string {
        $now = new DateTimeImmutable();
        $builder = $this->_configuration->builder()
            ->issuedBy($issuer)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($expiryDate);

        foreach ($claims as $claim => $value) {
            $builder = $builder->withClaim($claim, $value);
        }

        return $builder->getToken($this->_configuration->signer(), $this->_configuration->signingKey())
            ->toString();
    }

    /**
     * Get token claim
     */
    public function getTokenClaim(string $token, string $claim): mixed
    {
        try {
            $token = $this->_configuration->parser()->parse($token);
        } catch (Exception $e) {
            return null;
        }

        return $token->claims()->get($claim);
    }
}
