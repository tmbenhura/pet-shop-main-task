<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Tests\TestCase;

/**
 * @group User
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Log into user account.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_user_can_log_into_account(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email, 'password' => 'password']
        );

        $response->assertStatus(200);
    }

    /**
     * Login fails for admins.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_login_fails_for_admins(): void
    {
        $user = User::factory()->create(
            ['is_admin' => true]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email, 'password' => 'password']
        );

        $response->assertStatus(401);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Failed to authenticate user',
                    ]
                ]
            ]
        );
    }

    /**
     * Login fails with invalid email.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_login_fails_with_invalid_email(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => 'com', 'password' => 'password']
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '422',
                        'title' => 'Unprocessable Entity',
                        'description' => 'The email field must be a valid email address.',
                    ]
                ]
            ]
        );
    }

    /**
     * Login fails with incorrect email.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_login_fails_with_incorrect_email(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email.'.com', 'password' => 'password']
        );

        $response->assertStatus(401);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Failed to authenticate user',
                    ]
                ]
            ]
        );
    }


    /**
     * Login fails with missing password.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_login_fails_with_missing_password(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email, 'password' => '']
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '422',
                        'title' => 'Unprocessable Entity',
                        'description' => 'The password field is required.',
                    ]
                ]
            ]
        );
    }

    /**
     * Login fails with incorrect password.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_login_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email, 'password' => 'rum']
        );

        $response->assertStatus(401);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Failed to authenticate user',
                    ]
                ]
            ],
        );
    }

    /**
     * Login returns token on success.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_login_returns_token_on_success(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email, 'password' => 'password']
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.token')
        );
    }

    /**
     * User token claims are compliant.
     *
     * @bodyParam email string required The email of the user
     * @bodyParam password string required
     */
    public function test_user_token_claims_are_compliant(): void
    {
        $user = User::factory()->create(
            ['is_admin' => false]
        );

        $response = $this->postJson(
            route('api.user.login'),
            ['email' => $user->email, 'password' => 'password']
        );

        $response->assertStatus(200);

        $rawToken = $response->getData()->data->token;
        $parser = new Parser(new JoseEncoder());
        try {
            $decodedToken = $parser->parse($rawToken);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
        }

        $claims = $decodedToken->claims();
        $this->assertEquals(config('app.url'), $claims->get('iss'));
        $this->assertEquals($user->uuid, $claims->get('user_uuid'));
        $this->assertEquals(['user'], $claims->get('roles'));
    }
}
