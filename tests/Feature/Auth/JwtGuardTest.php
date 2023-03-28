<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class JwtGuardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Api guard can resolves users.
     */
    public function test_api_guard_can_resolve_users(): void
    {
        Route::middleware('auth:api')->get(
            '/test',
            fn () => ['user' => auth()->id()]
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
        $response->assertJson(['user' => $user->id]);
    }

    /**
     * Api guard rejects guests.
     */
    public function test_api_guard_rejects_guests(): void
    {
        Route::middleware('auth:api')->get(
            '/test',
            fn () => ['user' => auth()->id()]
        );

        $response = $this->getJson(
            '/test',
        );

        $response->assertStatus(401);
    }

    /**
     * Api guard rejects invalid tokens.
     */
    public function test_api_guard_rejects_invalid_tokens(): void
    {
        Route::middleware('auth:api')->get(
            '/test',
            fn () => ['user' => auth()->id()]
        );

        $response = $this->withHeader('Authorization', "Bearer INVALID")
            ->getJson(
                '/test',
            );

        $response->assertStatus(401);
    }
}
