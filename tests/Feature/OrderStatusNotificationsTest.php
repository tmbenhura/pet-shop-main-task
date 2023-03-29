<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusNotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Orders status change sends webhook notification.
     */
    public function test_order_status_change_sends_webhook_notification(): void
    {
        config()->set('order-status-notifications.webhook_url', 'http://webhook.test');
        Http::fake(['webhook.test' => Http::response('ok')]);

        $order = Order::factory()->create();
        $order->order_status_uuid = OrderStatus::factory()->create()->uuid;
        $order->save();

        Http::assertSent(function (Request $request) {
            return $request->url() == 'http://webhook.test';
        });
    }
}
