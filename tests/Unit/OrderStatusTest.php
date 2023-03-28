<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\OrderStatus;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Order status factory generates required fields.
     */
    public function test_factory_generates_required_fields(): void
    {
        $status = OrderStatus::factory()->create();

        $this->assertNotEmpty($status->title);
    }

    /**
     * Order status titles are unique.
     */
    public function test_order_status_titles_are_unique(): void
    {
        try {
            OrderStatus::factory()->create(['title' => 'open']);
            OrderStatus::factory()->create(['title' => 'open']);
        } catch (Exception $e) {}

        $this->assertDatabaseCount('order_statuses', 1);
    }
}
