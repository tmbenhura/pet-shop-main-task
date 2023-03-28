<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Order factory generates required fields.
     */
    public function test_factory_generates_required_fields(): void
    {
        $order = Order::factory()->create();

        $this->assertNotEmpty($order->uuid);
        $this->assertNotNull($order->user_id);
        $this->assertNotNull($order->order_status_uuid);
        $this->assertNotNull($order->payment_uuid);
        $this->assertNotEmpty($order->billing_address);
        $this->assertNotEmpty($order->shipping_address);
        $this->assertNotEmpty($order->delivery_fee_cents);
        $this->assertNotNull($order->amount_cents);
        $this->assertNotEmpty($order->shipped_at);
    }

    /**
     * Order has user relationship.
     */
    public function test_order_has_user_relationship(): void
    {
        $order = Order::factory()->create();

        $this->assertTrue($order->user() instanceof BelongsTo);

        $user = User::factory()->create();
        $order->user()->associate($user);

        $this->assertEquals($user->id, $order->user_id);
    }

    /**
     * Order has status relationship.
     */
    public function test_order_has_status_relationship(): void
    {
        $order = Order::factory()->create();

        $this->assertTrue($order->status() instanceof BelongsTo);

        $status = OrderStatus::factory()->create();
        $order->status()->associate($status);

        $this->assertEquals($status->uuid, $order->order_status_uuid);
    }
}
