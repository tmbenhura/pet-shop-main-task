<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\JwtTokenManager;
use App\Services\TokenManager;
use Carbon\CarbonImmutable;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Tests\TestCase;

class TokenManagerTest extends TestCase
{
    /**
     * Token manager implements jwt token manager interface.
     */
    public function test_token_manager_implements_jwt_token_manager_interface(): void
    {
        $this->assertTrue(in_array(JwtTokenManager::class, class_implements(TokenManager::class)));
    }

    /**
     * Token manager can issue a token.
     */
    public function test_token_manager_can_issue_a_token(): void
    {
        $expiryDate = CarbonImmutable::now()->addDays(5);
        $token = app(TokenManager::class)->issueToken(
            'http://jwt.test',
            $expiryDate,
            [
                'uid' => 1,
                'roles' => ['admin'],
            ]
        );

        $parser = new Parser(new JoseEncoder());
        try {
            $decodedToken = $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
        }

        $claims = $decodedToken->claims();
        $this->assertEquals('http://jwt.test', $claims->get('iss'));
        $this->assertEquals(1, $claims->get('uid'));
        $this->assertEquals(['admin'], $claims->get('roles'));
    }

    /**
     * Token manager can get token claims.
     */
    public function test_token_manager_can_get_token_claims(): void
    {
        $expiryDate = CarbonImmutable::now()->addDays(5);
        /** @var TokenManager */
        $tokenManager = app(TokenManager::class);
        $token = $tokenManager->issueToken(
            'http://jwt.test',
            $expiryDate,
            [
                'uid' => 1,
                'roles' => ['admin'],
            ]
        );

        $this->assertEquals('http://jwt.test', $tokenManager->getTokenClaim($token, 'iss'));
        $this->assertEquals(1, $tokenManager->getTokenClaim($token, 'uid'));
        $this->assertEquals(['admin'], $tokenManager->getTokenClaim($token, 'roles'));
    }

    /**
     * Token manager returns null for claim on invalid token.
     */
    public function test_token_manager_returns_null_for_claim_on_invalid_token(): void
    {
        $claim = app(TokenManager::class)->getTokenClaim('INVALID', 'uid');

        $this->assertNull($claim);
    }
}
