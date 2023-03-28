<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticateRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate role middleware allows admins.
     */
    public function test_authenticate_role_middleware_allows_admins(): void
    {
        Route::middleware('auth.role:admin')->get(
            '/test',
            fn () => ['success' => true]
        );

        $user = User::factory()->create(
            ['is_admin' => true]
        );

        $response = $this->postJson(
            route('api.admin.login'),
            ['email' => $user->email, 'password' => 'password']
        );

        $response->assertStatus(200);

        $token = $response->getData()->data->token;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson(
                '/test',
            );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
