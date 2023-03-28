<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * User has roles attribute.
     */
    public function test_user_has_roles_attribute(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertEquals(['admin'], $admin->roles);
        $this->assertEquals(['user'], $user->roles);
    }

    /**
     * User has orders relationship.
     */
    public function test_user_has_orders_relationship(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($user->orders() instanceof HasMany);

        Order::factory()->create(['user_id' => $user->id]);

        $this->assertEquals(1, $user->orders()->count());
    }
}
