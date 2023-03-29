<?php

namespace Tests\Feature\Seeders;

use Database\Seeders\PaymentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Payments can be seeded.
     */
    public function test_payments_can_be_seeded(): void
    {
        $this->seed(PaymentSeeder::class);

        collect(
            [
                'credit card',
                'cash on delivery',
                'bank transfer',
            ]
        )->each(
            fn (string $type) => $this->assertDatabaseHas('payments', ['type' => $type])
        );
    }
}
