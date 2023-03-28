<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group User
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Log into user account.
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
     * Login fails with incorrect email.
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
     * Login fails with incorrect password.
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
}
