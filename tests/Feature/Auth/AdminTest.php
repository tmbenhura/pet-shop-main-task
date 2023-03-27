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
}
