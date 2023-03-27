<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Admin
 */
class AdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Log into admin account.
     */
    public function test_admin_can_log_into_account(): void
    {
        $user = User::factory()->create(
            ['is_admin' => true]
        );

        $response = $this->post(
            route('api.login'),
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
            ['is_admin' => true]
        );

        $response = $this->post(
            route('api.login'),
            ['email' => $user->email.'.com', 'password' => 'password']
        );

        $response->assertStatus(401);
        $response->assertJson(
            [
                'success' => false,
                'message' => 'Failed to authenticate user'
            ]
        );
    }

    /**
     * Login fails with incorrect password.
     */
    public function test_login_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create(
            ['is_admin' => true]
        );

        $response = $this->post(
            route('api.login'),
            ['email' => $user->email, 'password' => 'rum']
        );

        $response->assertStatus(401);
        $response->assertJson(
            [
                'success' => false,
                'message' => 'Failed to authenticate user'
            ]
        );
    }
}
