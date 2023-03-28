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

    /**
     * Authenticate role middleware disallows invalid tokens.
     */
    public function test_authenticate_role_middleware_disallows_invalid_tokens(): void
    {
        Route::middleware('auth.role:admin')->get(
            '/test',
            fn () => ['success' => true]
        );

        $response = $this->withHeader('Authorization', 'Bearer INVALID')
            ->getJson(
                '/test',
            );

        $response->assertStatus(401);
    }

    /**
     * Authenticate role middleware disallows guests.
     */
    public function test_authenticate_role_middleware_disallows_guests(): void
    {
        Route::middleware('auth.role:admin')->get(
            '/test',
            fn () => ['success' => true]
        );

        $response = $this->getJson(
            '/test',
        );

        $response->assertStatus(401);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Unauthorized'
                    ]
                ]
            ]
        );
    }

    /**
     * Authenticate role middleware disallows users when expecting admins.
     */
    public function test_authenticate_role_middleware_disallows_users_when_expecting_admins(): void
    {
        Route::middleware('auth.role:admin')->get(
            '/test',
            fn () => ['success' => true]
        );

        $user = User::factory()->create(
            ['is_admin' => false]
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

        $response->assertStatus(401);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Unauthorised'
                    ]
                ]
            ]
        );
    }
}
