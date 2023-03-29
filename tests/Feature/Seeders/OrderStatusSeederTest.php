<?php

namespace Tests\Feature\Seeders;

use Database\Seeders\OrderStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Order statuses can be seeded.
     */
    public function test_order_statuses_can_be_seeded(): void
    {
        $this->seed(OrderStatusSeeder::class);

        collect(
            [
                'open',
                'pending payment',
                'paid',
                'shipped',
                'cancelled',
            ]
        )->each(
            fn (string $title) => $this->assertDatabaseHas('order_statuses', ['title' => $title])
        );
    }
}
