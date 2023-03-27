<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User factory generates required fields.
     */
    public function test_factory_generates_required_fields(): void
    {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->first_name);
        $this->assertNotEmpty($user->last_name);
        $this->assertNotNull($user->is_admin);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->email_verified_at);
        $this->assertNotEmpty($user->address);
        $this->assertNotEmpty($user->phone_number);
        $this->assertNotNull($user->is_marketing);
        $this->assertNotEmpty($user->last_login_at);
    }

    /**
     * User model generates uuid.
     */
    public function test_model_generates_uuid(): void
    {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->uuid);
        $this->assertTrue(Str::isUuid($user->uuid));
    }
}
