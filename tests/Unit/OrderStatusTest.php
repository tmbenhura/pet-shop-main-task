<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\OrderStatus;
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
}
