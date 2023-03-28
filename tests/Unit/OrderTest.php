<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
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
}
