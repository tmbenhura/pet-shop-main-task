<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{
     *     user_id: int,
     *     order_status_uuid: string,
     *     payment_uuid: string,
     *     billing_address: string,
     *     shipping_address: string,
     *     delivery_fee_cents: int,
     *     amount_cents: int,
     *     shipped_at: \Illuminate\Support\Carbon
     * }
     */
    public function definition(): array
    {
        $amount = rand(1, 1000);

        return [
            'user_id' => fn (): int => User::factory()->create()->id,
            'order_status_uuid' => fn (): string => OrderStatus::factory()->create()->uuid,
            'payment_uuid' => fn (): string => Payment::factory()->create()->uuid,
            'billing_address' => fake()->streetAddress(),
            'shipping_address' => fake()->streetAddress(),
            'delivery_fee_cents' => $amount > 500 ? 0 : 1500,
            'amount_cents' => $amount * 100,
            'shipped_at' => now(),
        ];
    }
}
